<?php

namespace Tests\Feature;

use App\Models\Students\Student;
use Tests\TestCase;

class StudentStudyModeTest extends TestCase
{
    /**
     * Test that student model handles study_mode attribute and helper methods correctly.
     */
    public function test_student_study_mode_helpers_and_labels(): void
    {
        $fullTimeStudent = new Student([
            'study_mode' => 'full_time',
        ]);

        $this->assertTrue($fullTimeStudent->isFullTime());
        $this->assertFalse($fullTimeStudent->isPartTime());
        $this->assertSame('ពេញម៉ោង (Full-Time)', $fullTimeStudent->study_mode_label);

        $partTimeStudent = new Student([
            'study_mode' => 'part_time',
        ]);

        $this->assertFalse($partTimeStudent->isFullTime());
        $this->assertTrue($partTimeStudent->isPartTime());
        $this->assertSame('ក្រៅម៉ោង (Part-Time)', $partTimeStudent->study_mode_label);

        $dualStudent = new Student([
            'study_mode' => 'dual',
        ]);

        $this->assertFalse($dualStudent->isFullTime());
        $this->assertFalse($dualStudent->isPartTime());
        $this->assertTrue($dualStudent->isDual());
        $this->assertSame('ពេញម៉ោង និង ក្រៅម៉ោង (Dual Study)', $dualStudent->study_mode_label);
    }

    /**
     * Test that student study_mode defaults to full_time when not specified.
     */
    public function test_student_defaults_to_full_time(): void
    {
        $student = new Student;

        $this->assertTrue($student->isFullTime());
        $this->assertFalse($student->isPartTime());
        $this->assertSame('ពេញម៉ោង (Full-Time)', $student->study_mode_label);
    }

    /**
     * Test that study_mode query scope filters correctly.
     */
    public function test_student_study_mode_scope(): void
    {
        $queryFull = Student::query()->studyMode('full_time')->toSql();
        $this->assertStringContainsString('study_mode', $queryFull);

        $queryAll = Student::query()->studyMode(null)->toSql();
        $this->assertStringNotContainsString('study_mode', $queryAll);
    }
}
