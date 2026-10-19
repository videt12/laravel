<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Dynamic Management Controller — stub for future entity management.
 * This controller will be fully implemented in a subsequent phase.
 */
class ManagementController extends Controller
{
    public function index(Request $request, string $entity)
    {
        return match ($entity) {
            'users' => redirect()->route('settings.index', ['tab' => 'users']),
            'roles' => redirect()->route('settings.index', ['tab' => 'roles']),
            'settings' => redirect()->route('settings.index', ['tab' => 'system']),
            'school-profile' => redirect()->route('settings.index', ['tab' => 'school-profile']),
            'academic-years' => redirect()->route('academics.index', ['tab' => 'academic-years']),
            default => redirect()->route('students.index')
                ->with('error', "The '{$entity}' management module is not yet implemented."),
        };
    }

    public function create(Request $request, string $entity)
    {
        return redirect()->route('students.index');
    }

    public function store(Request $request, string $entity)
    {
        return redirect()->route('students.index');
    }

    public function show(Request $request, string $entity, $id)
    {
        return redirect()->route('students.index');
    }

    public function edit(Request $request, string $entity, $id)
    {
        return redirect()->route('students.index');
    }

    public function update(Request $request, string $entity, $id)
    {
        return redirect()->route('students.index');
    }

    public function destroy(Request $request, string $entity, $id)
    {
        return redirect()->route('students.index');
    }
}
