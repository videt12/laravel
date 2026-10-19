<?php

namespace Tests\Feature;

use App\Models\Payroll\SalaryComponent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SalaryComponentFormTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('schools')) {
            Schema::create('schools', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('Test School');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Test create view contains simplified layout.
     */
    public function test_salary_component_create_view_contains_simplified_structure(): void
    {
        $this->withViewErrors([]);

        $view = $this->view('payroll.salary-components.create', [
            'structures' => collect(),
            'selectedStructure' => null,
            'defaultCode' => 'TS00001',
        ]);

        // Code field is present and readonly
        $view->assertSee('TS00001');
        $view->assertSee('readonly', false);

        // Hidden order and is_taxable are present
        $view->assertSee('type="hidden" name="order"', false);
        $view->assertSee('type="hidden" name="is_taxable"', false);

        // Core fields are kept
        $view->assertSee('name="name"', false);
        $view->assertSee('name="type"', false);
        $view->assertSee('name="calculation_type"', false);
        $view->assertSee('name="amount"', false);
        $view->assertSee('name="is_active"', false);
        $view->assertSee('name="description"', false);

        // No visible input for order or taxable
        $view->assertDontSee('type="number" name="order"', false);
        $view->assertDontSee('type="checkbox" name="is_taxable"', false);
    }

    /**
     * Test edit view contains simplified layout.
     */
    public function test_salary_component_edit_view_contains_simplified_structure(): void
    {
        $this->withViewErrors([]);

        $component = new SalaryComponent([
            'name' => 'ប្រាក់បន្ថែម',
            'code' => 'TS00002',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'amount' => 100,
            'percentage' => null,
            'order' => 0,
            'is_taxable' => false,
            'is_active' => true,
            'description' => 'ការពិពណ៌នា',
        ]);
        $component->id = 1;
        $component->exists = true;

        $view = $this->view('payroll.salary-components.edit', [
            'salary_component' => $component,
            'structures' => collect(),
        ]);

        // Code field is present and readonly
        $view->assertSee('TS00002');
        $view->assertSee('readonly', false);

        // Hidden order and is_taxable are present
        $view->assertSee('type="hidden" name="order"', false);
        $view->assertSee('type="hidden" name="is_taxable"', false);

        // Core fields are kept
        $view->assertSee('name="name"', false);
        $view->assertSee('name="type"', false);
        $view->assertSee('name="calculation_type"', false);
        $view->assertSee('name="amount"', false);
        $view->assertSee('name="is_active"', false);

        // No visible input for order or taxable
        $view->assertDontSee('type="number" name="order"', false);
        $view->assertDontSee('type="checkbox" name="is_taxable"', false);
    }

    /**
     * Test model fillable attributes support the simplified fields.
     */
    public function test_salary_component_model_attributes(): void
    {
        $component = new SalaryComponent([
            'name' => 'ប្រាក់ឧបត្ថម្ភ',
            'code' => 'TS00003',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'amount' => 75.50,
            'order' => 0,
            'is_taxable' => false,
            'is_active' => true,
        ]);

        $this->assertSame('ប្រាក់ឧបត្ថម្ភ', $component->name);
        $this->assertSame('TS00003', $component->code);
        $this->assertEquals(75.50, $component->amount);
        $this->assertSame(0, $component->order);
        $this->assertFalse($component->is_taxable);
        $this->assertTrue($component->is_active);
    }
}
