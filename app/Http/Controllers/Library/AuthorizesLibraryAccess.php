<?php

namespace App\Http\Controllers\Library;

use Illuminate\Support\Facades\DB;

trait AuthorizesLibraryAccess
{
    /**
     * Authorize full library management access.
     * Allowed roles: Super Admin, Admin, Principal, Librarian.
     * Unauthorized roles are denied with HTTP 403.
     */
    protected function authorizeLibraryManagement(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        $allowedRoles = ['super-admin', 'admin', 'principal', 'librarian'];

        $isAuthorized = $user->roles()
            ->where(function ($q) use ($allowedRoles) {
                $q->whereIn('slug', $allowedRoles)
                    ->orWhereIn(DB::raw('LOWER(name)'), ['super admin', 'admin', 'principal', 'librarian']);
            })->exists();

        if (! $isAuthorized) {
            abort(403, 'Unauthorized access: You do not have permission to manage the Library module.');
        }
    }

    /**
     * Authorize borrower viewing history.
     * Managers (Super Admin, Admin, Principal, Librarian) can view any borrower.
     * Teachers and Students may ONLY view their own history (target user_id matches authenticated user).
     */
    protected function authorizeBorrowerAccess(int $targetUserId): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        // Managers can view any borrower
        $managerRoles = ['super-admin', 'admin', 'principal', 'librarian'];
        $isManager = $user->roles()
            ->where(function ($q) use ($managerRoles) {
                $q->whereIn('slug', $managerRoles)
                    ->orWhereIn(DB::raw('LOWER(name)'), ['super admin', 'admin', 'principal', 'librarian']);
            })->exists();

        if ($isManager) {
            return;
        }

        // Teachers and Students can only view their own history
        $borrowerRoles = ['teacher', 'student'];
        $isBorrower = $user->roles()
            ->where(function ($q) use ($borrowerRoles) {
                $q->whereIn('slug', $borrowerRoles)
                    ->orWhereIn(DB::raw('LOWER(name)'), ['teacher', 'student']);
            })->exists();

        if ($isBorrower && $user->id === $targetUserId) {
            return;
        }

        abort(403, 'Unauthorized access: You may only view your own library borrowing history.');
    }
}
