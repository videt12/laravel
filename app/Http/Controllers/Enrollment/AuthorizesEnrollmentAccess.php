<?php

namespace App\Http\Controllers\Enrollment;

use Illuminate\Support\Facades\DB;

trait AuthorizesEnrollmentAccess
{
    /**
     * Authorize administrative enrollment operations (create, store, edit, update, delete, promote, transfer, withdraw).
     * Only Admin, Super Admin, and Principal can modify student academic progression.
     */
    protected function authorizeEnrollmentManagement(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        $adminRoles = ['admin', 'super-admin', 'super_admin', 'principal'];
        $isAuthorized = $user->roles()
            ->where(function ($q) use ($adminRoles) {
                $q->whereIn('slug', $adminRoles)
                    ->orWhereIn(DB::raw('LOWER(name)'), ['admin', 'super admin', 'principal']);
            })->exists();

        if (! $isAuthorized) {
            abort(403, 'Unauthorized access: You do not have permission to alter enrollment or academic records.');
        }
    }

    /**
     * Authorize viewing enrollment records.
     */
    protected function authorizeEnrollmentView(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        if (! $user->canAccess(['enrollments.view', 'enrollments'])) {
            abort(403, 'Unauthorized access: You do not have permission to access enrollment management.');
        }
    }
}
