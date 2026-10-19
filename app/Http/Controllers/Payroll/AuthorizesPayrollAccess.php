<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Support\Facades\DB;

trait AuthorizesPayrollAccess
{
    /**
     * Authorize access to payroll and compensation resources.
     * Allowed roles: Super Admin, Admin, Principal, Accountant.
     * Unauthorized roles are denied with HTTP 403.
     */
    protected function authorizePayrollAccess(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        $payrollRoles = ['admin', 'super-admin', 'super_admin', 'principal', 'accountant'];

        $isAuthorized = $user->roles()
            ->where(function ($q) use ($payrollRoles) {
                $q->whereIn('slug', $payrollRoles)
                    ->orWhereIn(DB::raw('LOWER(name)'), ['admin', 'super admin', 'principal', 'accountant']);
            })->exists();

        if (! $isAuthorized) {
            abort(403, 'Unauthorized access: You do not have permission to access Payroll management.');
        }
    }
}
