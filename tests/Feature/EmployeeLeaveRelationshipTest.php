<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeLeave;
use App\Models\Employees\LeaveType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class EmployeeLeaveRelationshipTest extends TestCase
{
    /**
     * Test employee leave belongsTo employee with trashed records.
     */
    public function test_employee_leave_employee_relation_includes_trashed(): void
    {
        $leave = new EmployeeLeave;
        $relation = $leave->employee();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('employee_id', $relation->getForeignKeyName());
    }

    /**
     * Test employee belongsTo user relation.
     */
    public function test_employee_user_relation(): void
    {
        $employee = new Employee;
        $relation = $employee->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
    }

    /**
     * Test leaves index view renders safely when employee is null.
     */
    public function test_leaves_index_view_renders_without_employee(): void
    {
        $leaveWithoutEmployee = new EmployeeLeave([
            'employee_id' => null,
            'leave_type_id' => 1,
            'start_date' => now()->startOfDay(),
            'end_date' => now()->addDays(2)->startOfDay(),
            'reason' => 'Doctor appointment',
            'status' => 'Pending',
        ]);
        $leaveWithoutEmployee->id = 999;
        $leaveWithoutEmployee->setRelation('employee', null);

        $leaveType = new LeaveType(['name' => 'Sick Leave']);
        $leaveWithoutEmployee->setRelation('leave_type', $leaveType);

        $paginator = new LengthAwarePaginator(
            items: [$leaveWithoutEmployee],
            total: 1,
            perPage: 15,
            currentPage: 1
        );

        $view = $this->view('employees.leaves.index', ['leaves' => $paginator]);

        $view->assertSee('មិនស្គាល់');
        $view->assertSee('Doctor appointment');
    }
}
