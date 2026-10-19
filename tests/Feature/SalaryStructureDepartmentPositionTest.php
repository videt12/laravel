<?php

namespace Tests\Feature;

use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use App\Models\Payroll\SalaryStructure;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class SalaryStructureDepartmentPositionTest extends TestCase
{
    /**
     * Test salary structure has department and position in fillable and casts.
     */
    public function test_salary_structure_department_and_position_fillable_and_casts(): void
    {
        $structure = new SalaryStructure([
            'name' => 'គ្រូបង្រៀនកម្រិត A',
            'code' => 'TSG00001',
            'base_salary' => 500,
            'employee_department_id' => 2,
            'employee_position_id' => 3,
            'is_active' => true,
        ]);

        $this->assertSame(2, $structure->employee_department_id);
        $this->assertSame(3, $structure->employee_position_id);
        $this->assertTrue(in_array('employee_department_id', $structure->getFillable()));
        $this->assertTrue(in_array('employee_position_id', $structure->getFillable()));
    }

    /**
     * Test salary structure relationships for department and position.
     */
    public function test_salary_structure_relationships(): void
    {
        $structure = new SalaryStructure;

        $deptRelation = $structure->employee_department();
        $this->assertInstanceOf(BelongsTo::class, $deptRelation);
        $this->assertInstanceOf(EmployeeDepartment::class, $deptRelation->getRelated());
        $this->assertSame('employee_department_id', $deptRelation->getForeignKeyName());

        $deptAlias = $structure->department();
        $this->assertInstanceOf(BelongsTo::class, $deptAlias);

        $posRelation = $structure->employee_position();
        $this->assertInstanceOf(BelongsTo::class, $posRelation);
        $this->assertInstanceOf(EmployeePosition::class, $posRelation->getRelated());
        $this->assertSame('employee_position_id', $posRelation->getForeignKeyName());

        $posAlias = $structure->position();
        $this->assertInstanceOf(BelongsTo::class, $posAlias);
    }
}
