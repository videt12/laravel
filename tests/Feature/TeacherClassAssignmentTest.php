<?php

namespace Tests\Feature;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\TeacherAssignment;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeePosition;
use Tests\TestCase;

class TeacherClassAssignmentTest extends TestCase
{
    /**
     * Test teacher assignment model attributes and school_class relation.
     */
    public function test_teacher_assignment_school_class_relation_and_accessor(): void
    {
        $assignment = new TeacherAssignment([
            'employee_id' => 1,
            'class_id' => 5,
            'academic_year_id' => 2,
        ]);

        $this->assertSame(5, $assignment->class_id);
        $this->assertSame(1, $assignment->employee_id);
        $this->assertSame(2, $assignment->academic_year_id);

        $schoolClass = new SchoolClass(['id' => 5]);
        $assignment->setRelation('school_class', $schoolClass);

        $this->assertSame($schoolClass, $assignment->class);
    }

    /**
     * Test school class has teacher_assignments relation.
     */
    public function test_school_class_teacher_assignments_relation(): void
    {
        $class = new SchoolClass;
        $relation = $class->teacher_assignments();

        $this->assertSame('class_id', $relation->getForeignKeyName());
    }

    /**
     * Test Employee isTeacher detection.
     */
    public function test_employee_is_teacher_detection(): void
    {
        $employee = new Employee;
        $position = new EmployeePosition(['name' => 'Teacher', 'code' => 'POS-TCHR']);
        $employee->setRelation('employee_position', $position);

        $this->assertTrue($employee->isTeacher());

        $nonTeacherPos = new EmployeePosition(['name' => 'Accountant', 'code' => 'POS-ACCT']);
        $employee->setRelation('employee_position', $nonTeacherPos);
        $this->assertFalse($employee->isTeacher());
    }
}
