<?php

namespace Tests\Unit;

use App\Http\Controllers\Employees\EmployeeAttendanceController;
use Tests\TestCase;

class StaffAttendanceShiftSettingsTest extends TestCase
{
    /**
     * Test timeToMinutes converts 24-hour time to correct minutes.
     */
    public function test_time_to_minutes_calculates_correctly(): void
    {
        $this->assertSame(410, EmployeeAttendanceController::timeToMinutes('06:50'));
        $this->assertSame(630, EmployeeAttendanceController::timeToMinutes('10:30'));
        $this->assertSame(790, EmployeeAttendanceController::timeToMinutes('13:10'));
        $this->assertSame(990, EmployeeAttendanceController::timeToMinutes('16:30'));
        $this->assertSame(720, EmployeeAttendanceController::timeToMinutes('12:00'));
        $this->assertSame(0, EmployeeAttendanceController::timeToMinutes(null));
        $this->assertSame(0, EmployeeAttendanceController::timeToMinutes(''));
    }

    /**
     * Test getShiftSettings returns valid default keys and values.
     */
    public function test_get_shift_settings_returns_default_structure(): void
    {
        $settings = EmployeeAttendanceController::getShiftSettings();

        $this->assertIsArray($settings);
        $this->assertArrayHasKey('staff_morning_in', $settings);
        $this->assertArrayHasKey('staff_morning_grace', $settings);
        $this->assertArrayHasKey('staff_morning_out', $settings);
        $this->assertArrayHasKey('staff_morning_scan_in_start', $settings);
        $this->assertArrayHasKey('staff_morning_scan_in_end', $settings);
        $this->assertArrayHasKey('staff_morning_scan_out_start', $settings);
        $this->assertArrayHasKey('staff_morning_scan_out_end', $settings);

        $this->assertArrayHasKey('staff_afternoon_in', $settings);
        $this->assertArrayHasKey('staff_afternoon_grace', $settings);
        $this->assertArrayHasKey('staff_afternoon_out', $settings);
        $this->assertArrayHasKey('staff_afternoon_scan_in_start', $settings);
        $this->assertArrayHasKey('staff_afternoon_scan_in_end', $settings);
        $this->assertArrayHasKey('staff_afternoon_scan_out_start', $settings);
        $this->assertArrayHasKey('staff_afternoon_scan_out_end', $settings);

        $this->assertArrayHasKey('staff_shift_split', $settings);

        $this->assertNotEmpty($settings['staff_morning_in']);
        $this->assertNotEmpty($settings['staff_morning_out']);
        $this->assertNotEmpty($settings['staff_morning_scan_in_start']);
        $this->assertNotEmpty($settings['staff_morning_scan_out_start']);
        $this->assertNotEmpty($settings['staff_afternoon_in']);
        $this->assertNotEmpty($settings['staff_afternoon_out']);
        $this->assertNotEmpty($settings['staff_afternoon_scan_in_start']);
        $this->assertNotEmpty($settings['staff_afternoon_scan_out_start']);
        $this->assertNotEmpty($settings['staff_shift_split']);
    }

    /**
     * Test evaluateAttendanceScan determines correct actions and statuses.
     */
    public function test_evaluate_attendance_scan_in_morning_windows(): void
    {
        $settings = EmployeeAttendanceController::getShiftSettings();

        // 1. Scan-in window (07:15 AM) - No existing record => allowed in
        $now = \Carbon\Carbon::create(2026, 10, 15, 7, 15, 0);
        $eval = EmployeeAttendanceController::evaluateAttendanceScan($settings, $now, null);
        $this->assertTrue($eval['can_record']);
        $this->assertSame('morning_in', $eval['action']);
        $this->assertSame('present', $eval['status']);

        // 2. Duplicate scan-in (07:20 AM) - Already checked in => rejected as duplicate
        $mockAttendance = new \App\Models\EmployeeAttendance([
            'in_time' => '07:15:00',
            'out_time' => null,
        ]);
        $evalDup = EmployeeAttendanceController::evaluateAttendanceScan($settings, $now, $mockAttendance);
        $this->assertFalse($evalDup['can_record']);
        $this->assertSame('morning_in_duplicate', $evalDup['error_code']);
        $this->assertStringContainsString('បានស្កេនចូលរួចហើយ', $evalDup['message']);

        // 3. Early check-out attempt (09:40 AM) - Has checked in, before scan-out start => rejected with "not yet time"
        $midMorning = \Carbon\Carbon::create(2026, 10, 15, 9, 40, 0);
        $evalEarlyOut = EmployeeAttendanceController::evaluateAttendanceScan($settings, $midMorning, $mockAttendance);
        $this->assertFalse($evalEarlyOut['can_record']);
        $this->assertSame('morning_out_not_started', $evalEarlyOut['error_code']);
        $this->assertSame('មិនទាន់ដល់ពេលកំណត់', $evalEarlyOut['action_label']);
        $this->assertStringContainsString('មិនទាន់ដល់ពេលកំណត់ស្កេនចេញ', $evalEarlyOut['message']);

        // 4. Late check-in attempt (09:40 AM) - Missed scan-in window => rejected with "scan in expired"
        $evalExpiredIn = EmployeeAttendanceController::evaluateAttendanceScan($settings, $midMorning, null);
        $this->assertFalse($evalExpiredIn['can_record']);
        $this->assertSame('morning_in_expired', $evalExpiredIn['error_code']);
        $this->assertSame('ផុតកំណត់ស្កេនចូល', $evalExpiredIn['action_label']);
        $this->assertStringContainsString('បានផុតកំណត់ម៉ោងស្កេនចូល', $evalExpiredIn['message']);

        // 5. Scan-out window (11:30 AM) - Has checked in => allowed out
        $outTime = \Carbon\Carbon::create(2026, 10, 15, 11, 30, 0);
        $evalOut = EmployeeAttendanceController::evaluateAttendanceScan($settings, $outTime, $mockAttendance);
        $this->assertTrue($evalOut['can_record']);
        $this->assertSame('morning_out', $evalOut['action']);

        // 6. Scan-out window (11:30 AM) - Has NOT checked in => rejected as no in record
        $evalNoIn = EmployeeAttendanceController::evaluateAttendanceScan($settings, $outTime, null);
        $this->assertFalse($evalNoIn['can_record']);
        $this->assertSame('morning_no_in', $evalNoIn['error_code']);
        $this->assertStringContainsString('មិនអាចស្កេនចេញបានទេ', $evalNoIn['message']);
    }

    /**
     * Test getActiveShiftStatus correctly labels current period.
     */
    public function test_get_active_shift_status_identifies_periods(): void
    {
        $settings = EmployeeAttendanceController::getShiftSettings();

        // 07:00 AM should be Morning Scan In
        $morningIn = \Carbon\Carbon::create(2026, 10, 15, 7, 0, 0);
        $statusIn = EmployeeAttendanceController::getActiveShiftStatus($settings, $morningIn);
        $this->assertSame('morning_in', $statusIn['type']);

        // 09:45 AM should be Waiting for Scan Out / Between windows
        $waitingOut = \Carbon\Carbon::create(2026, 10, 15, 9, 45, 0);
        $statusWaiting = EmployeeAttendanceController::getActiveShiftStatus($settings, $waitingOut);
        $this->assertSame('morning_waiting_out', $statusWaiting['type']);

        // 11:30 AM should be Morning Scan Out
        $morningOut = \Carbon\Carbon::create(2026, 10, 15, 11, 30, 0);
        $statusOut = EmployeeAttendanceController::getActiveShiftStatus($settings, $morningOut);
        $this->assertSame('morning_out', $statusOut['type']);
    }
}
