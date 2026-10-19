<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryBookCopy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LibraryBookCopyController extends Controller
{
    use AuthorizesLibraryAccess;

    public function store(Request $request, LibraryBook $book)
    {
        $this->authorizeLibraryManagement();

        $validated = $request->validate([
            'accession_number' => 'required|string|max:50|unique:library_book_copies,accession_number',
            'status' => ['required', Rule::in(['available', 'issued', 'lost', 'damaged'])],
        ]);

        $book->library_book_copies()->create($validated);

        return redirect()->route('library.books.show', $book)
            ->with('success', "Physical copy '{$validated['accession_number']}' added successfully.");
    }

    public function update(Request $request, LibraryBookCopy $copy)
    {
        $this->authorizeLibraryManagement();

        $validated = $request->validate([
            'status' => ['required', Rule::in(['available', 'issued', 'lost', 'damaged'])],
        ]);

        // Do not allow status 'available' if an active unreturned issue exists
        if ($validated['status'] === 'available' && $copy->hasActiveIssue()) {
            return back()->with('error', 'Cannot set copy status to available while an active issue exists.');
        }

        $copy->update($validated);

        return back()->with('success', "Copy '{$copy->accession_number}' status updated to {$copy->status}.");
    }

    public function destroy(LibraryBookCopy $copy)
    {
        $this->authorizeLibraryManagement();

        // Cannot delete a copy with active unreturned issue
        if ($copy->hasActiveIssue()) {
            return back()->with('error', 'Cannot delete a physical copy that has an active unreturned loan.');
        }

        $book = $copy->library_book;
        $copy->delete();

        return redirect()->route('library.books.show', $book)
            ->with('success', 'Physical copy deleted successfully.');
    }
}
