<?php

namespace Tests\Feature;

use App\Models\Academics\Classroom;
use App\Models\Academics\SchoolClass;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class SchoolClassRoomAssignmentTest extends TestCase
{
    /**
     * Test school class has classroom relation and classroom_id fillable.
     */
    public function test_school_class_classroom_relation_and_fillable(): void
    {
        $class = new SchoolClass([
            'grade_id' => 1,
            'section_id' => 2,
            'academic_year_id' => 3,
            'classroom_id' => 4,
            'capacity' => 35,
        ]);

        $this->assertSame(4, $class->classroom_id);
        $this->assertTrue(in_array('classroom_id', $class->getFillable()));

        $relation = $class->classroom();
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('classroom_id', $relation->getForeignKeyName());
    }

    /**
     * Test classroom has classes relation.
     */
    public function test_classroom_classes_relation(): void
    {
        $classroom = new Classroom([
            'name' => 'Room 101',
            'building' => 'Building A',
            'capacity' => 40,
        ]);

        $relation = $classroom->classes();
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('classroom_id', $relation->getForeignKeyName());
    }

    /**
     * Test shift detection from section names.
     */
    public function test_detect_shift_and_labels(): void
    {
        $this->assertSame('morning', SchoolClass::detectShift('ក (ពេលព្រឹក)'));
        $this->assertSame('morning', SchoolClass::detectShift('Section Morning'));
        $this->assertSame('afternoon', SchoolClass::detectShift('ខ (ពេលរសៀល)'));
        $this->assertSame('afternoon', SchoolClass::detectShift('Day Shift A'));
        $this->assertSame('evening', SchoolClass::detectShift('គ (ពេលយប់)'));
        $this->assertSame('evening', SchoolClass::detectShift('ក្រៅម៉ោង'));
        $this->assertSame('all', SchoolClass::detectShift('Section A'));
        $this->assertSame('all', SchoolClass::detectShift(null));

        $this->assertSame('ពេលព្រឹក', SchoolClass::getShiftLabel('morning'));
        $this->assertSame('ពេលរសៀល', SchoolClass::getShiftLabel('afternoon'));
    }
}
