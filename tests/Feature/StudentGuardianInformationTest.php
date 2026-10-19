<?php

namespace Tests\Feature;

use App\Models\Students\Guardian;
use Tests\TestCase;

class StudentGuardianInformationTest extends TestCase
{
    /**
     * Test that Guardian model correctly fills and casts age and occupation.
     */
    public function test_guardian_handles_age_and_occupation_attributes(): void
    {
        $guardian = new Guardian([
            'age' => '45',
            'occupation' => 'គ្រូបង្រៀន (Teacher)',
        ]);

        $this->assertSame(45, $guardian->age);
        $this->assertSame('គ្រូបង្រៀន (Teacher)', $guardian->occupation);
        $this->assertContains('age', $guardian->getFillable());
        $this->assertContains('occupation', $guardian->getFillable());
    }
}
