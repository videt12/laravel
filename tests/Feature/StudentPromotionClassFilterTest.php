<?php

namespace Tests\Feature;

use App\Http\Controllers\Enrollment\PromotionController;
use App\Models\Academics\SchoolClass;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Enrollment\StudentPromotion;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentPromotionClassFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('student_promotions');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Admin');
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
        });

        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->integer('capacity')->default(30);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('from_academic_year_id')->nullable();
            $table->unsignedBigInteger('to_academic_year_id')->nullable();
            $table->unsignedBigInteger('from_class_id');
            $table->unsignedBigInteger('to_class_id');
            $table->date('promotion_date')->nullable();
            $table->string('status')->default('Promoted');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('student_promotions');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    /**
     * Test that PromotionController lists all classes in dropdown and filters by to_class_id.
     */
    public function test_promotion_filters_by_to_class_and_lists_all_classes(): void
    {
        $admin = User::create(['name' => 'Admin User', 'email' => 'admin@test.com']);
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin->roles()->attach($role->id);

        $oldClass = SchoolClass::create(['id' => 10, 'capacity' => 30]);
        $newClass = SchoolClass::create(['id' => 20, 'capacity' => 30]);
        $emptyClass = SchoolClass::create(['id' => 30, 'capacity' => 30]);

        StudentPromotion::create([
            'from_class_id' => $oldClass->id,
            'to_class_id' => $newClass->id,
            'status' => 'Promoted',
        ]);

        $this->actingAs($admin);

        $controller = new PromotionController;
        $request = Request::create(route('promotions.index'), 'GET');
        $view = $controller->index($request);

        $classes = $view->getData()['classes'];
        $classIds = $classes->pluck('id')->toArray();

        // Dropdown displays all classes
        $this->assertContains($newClass->id, $classIds);
        $this->assertContains($oldClass->id, $classIds);
        $this->assertContains($emptyClass->id, $classIds);

        // When filtering by class_id = $oldClass->id, student promoted out of it does NOT show
        $oldFilterRequest = Request::create(route('promotions.index'), 'GET', ['class_id' => $oldClass->id]);
        $oldFilterView = $controller->index($oldFilterRequest);
        $this->assertSame(0, $oldFilterView->getData()['promotions']->total());

        // When filtering by class_id = $newClass->id, student promoted into it DOES show
        $newFilterRequest = Request::create(route('promotions.index'), 'GET', ['class_id' => $newClass->id]);
        $newFilterView = $controller->index($newFilterRequest);
        $this->assertSame(1, $newFilterView->getData()['promotions']->total());
        $this->assertSame($newClass->id, $newFilterView->getData()['promotions']->first()->to_class_id);
    }
}
