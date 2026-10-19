<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Update the authenticated user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'name.required' => 'សូមបញ្ចូលឈ្មោះអ្នកប្រើប្រាស់។',
            'email.required' => 'សូមបញ្ចូលអាសយដ្ឋានអ៊ីមែល។',
            'email.email' => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវទេ។',
            'email.unique' => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ។',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return back()->with('success', 'ព័ត៌មានគណនីត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់បច្ចុប្បន្ន។',
            'current_password.current_password' => 'ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវទេ។',
            'password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់ថ្មី។',
            'password.min' => 'ពាក្យសម្ងាត់ថ្មីត្រូវមានយ៉ាងតិច ៨ តួអក្សរ។',
            'password.confirmed' => 'ការបញ្ជាក់ពាក្យសម្ងាត់ថ្មីមិនត្រូវគ្នាទេ។',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'ពាក្យសម្ងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ!');
    }
}
