<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Support\Facades\DB;

trait AuthorizesFinanceAccess
{
    /**
     * Authorize access to finance resources.
     * Admin, Super Admin, Accountant, and Principal have access.
     * Teachers, students, and guardians are denied (HTTP 403).
     */
    protected function authorizeFinanceAccess(bool $allowPrincipal = true): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        $financeRoles = ['admin', 'super-admin', 'super_admin', 'accountant'];
        if ($allowPrincipal) {
            $financeRoles[] = 'principal';
        }

        $isAuthorized = $user->roles()
            ->where(function ($q) use ($financeRoles) {
                $q->whereIn('slug', $financeRoles)
                    ->orWhereIn(DB::raw('LOWER(name)'), ['admin', 'super admin', 'accountant', 'principal']);
            })->exists();

        if (! $isAuthorized) {
            abort(403, 'Unauthorized access: You do not have permission to access Finance management.');
        }
    }
}
