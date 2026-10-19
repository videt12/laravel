<?php

namespace Tests\Feature;

use App\Http\Controllers\Academics\SubjectController;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Subject;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SubjectCodeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('class_subjects');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('schools');

        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('class_subjects');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('schools');
        parent::tearDown();
    }

    /**
     * Test generateNextCode starts at S001 when no codes exist.
     */
    public function test_generate_next_code_starts_at_s001(): void
    {
        $code = Subject::generateNextCode();
        $this->assertSame('S001', $code);
    }

    /**
     * Test generateNextCode increments sequentially based on existing S-prefixed codes.
     */
    public function test_generate_next_code_increments_correctly(): void
    {
        Subject::create([
            'name' => 'Math',
            'code' => 'S001',
            'type' => 'Core',
        ]);

        $this->assertSame('S002', Subject::generateNextCode());

        Subject::create([
            'name' => 'English',
            'code' => 'S015',
            'type' => 'Core',
        ]);

        $this->assertSame('S016', Subject::generateNextCode());
    }

    /**
     * Test controller create action passes nextCode.
     */
    public function test_controller_create_passes_next_code(): void
    {
        $controller = new SubjectController;
        $view = $controller->create();

        $this->assertSame('S001', $view->getData()['nextCode']);
    }

    /**
     * Test controller store action auto-fills code if blank.
     */
    public function test_controller_store_auto_fills_code_if_blank(): void
    {
        $controller = new SubjectController;
        $request = Request::create(route('subjects.store'), 'POST', [
            'name' => 'Physics',
            'code' => '',
            'type' => 'Elective',
        ]);

        $response = $controller->store($request);

        $this->assertDatabaseHas('subjects', [
            'name' => 'Physics',
            'code' => 'S001',
        ]);
        $this->assertSame(route('academics.index', ['tab' => 'subjects']), $response->getTargetUrl());
    }

    /**
     * Test controller store action respects custom code if provided.
     */
    public function test_controller_store_respects_custom_code(): void
    {
        $controller = new SubjectController;
        $request = Request::create(route('subjects.store'), 'POST', [
            'name' => 'Chemistry',
            'code' => 'CHEM101',
            'type' => 'Elective',
        ]);

        $controller->store($request);

        $this->assertDatabaseHas('subjects', [
            'name' => 'Chemistry',
            'code' => 'CHEM101',
        ]);
    }

    /**
     * Test controller update action modifies subject and redirects to subjects tab.
     */
    public function test_controller_update_modifies_subject(): void
    {
        $subject = Subject::create([
            'name' => 'Old Name',
            'code' => 'S001',
            'type' => 'Core',
        ]);

        $controller = new SubjectController;
        $request = Request::create(route('subjects.update', $subject), 'PUT', [
            'name' => 'New Name',
            'code' => 'S001',
            'type' => 'Elective',
        ]);

        $response = $controller->update($request, $subject);

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'New Name',
            'type' => 'Elective',
        ]);
        $this->assertSame(route('academics.index', ['tab' => 'subjects']), $response->getTargetUrl());
    }

    /**
     * Test controller store and update syncs assigned classes.
     */
    public function test_controller_store_and_update_syncs_assigned_classes(): void
    {
        $class1 = SchoolClass::create(['id' => 1]);
        $class2 = SchoolClass::create(['id' => 2]);

        $controller = new SubjectController;
        $createRequest = Request::create(route('subjects.store'), 'POST', [
            'name' => 'Biology',
            'code' => 'S010',
            'type' => 'Core',
            'class_ids' => [1, 2],
        ]);

        $controller->store($createRequest);

        $subject = Subject::where('code', 'S010')->first();
        $this->assertNotNull($subject);
        $this->assertDatabaseHas('class_subjects', ['subject_id' => $subject->id, 'class_id' => 1]);
        $this->assertDatabaseHas('class_subjects', ['subject_id' => $subject->id, 'class_id' => 2]);

        // Update to only have class 2
        $updateRequest = Request::create(route('subjects.update', $subject), 'PUT', [
            'name' => 'Biology Updated',
            'code' => 'S010',
            'type' => 'Core',
            'class_ids' => [2],
        ]);

        $controller->update($updateRequest, $subject);

        $this->assertDatabaseMissing('class_subjects', ['subject_id' => $subject->id, 'class_id' => 1]);
        $this->assertDatabaseHas('class_subjects', ['subject_id' => $subject->id, 'class_id' => 2]);
    }

    /**
     * Test controller show action renders view without exception.
     */
    public function test_subject_show_renders_successfully(): void
    {
        $subject = Subject::create([
            'name' => 'KHMER',
            'code' => 'S001',
            'type' => 'Core',
        ]);

        $controller = new SubjectController;
        $view = $controller->show($subject);

        $this->assertSame('academics.subjects.show', $view->getName());
        $rendered = $view->render();
        $this->assertStringContainsString('KHMER', $rendered);
        $this->assertStringContainsString('S001', $rendered);
    }
}
