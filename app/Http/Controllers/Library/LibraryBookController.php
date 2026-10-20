<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibraryBookController extends Controller
{
    use AuthorizesLibraryAccess;

    public function index(Request $request)
    {
        $this->authorizeLibraryManagement();
        $this->cleanupTestBooks();
        $this->syncKhmerCategories();

        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $books = LibraryBook::with(['library_category', 'library_book_copies'])
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('author', 'like', "%{$term}%")
                        ->orWhere('publisher', 'like', "%{$term}%")
                        ->orWhere('isbn', 'like', "%{$term}%");
                });
            })
            ->when($categoryId, function ($query, $catId) {
                $query->where('library_category_id', $catId);
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $categories = LibraryCategory::all()->sortBy('name');

        return view('library.books.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    public function create()
    {
        $this->authorizeLibraryManagement();
        $this->syncKhmerCategories();

        $categories = LibraryCategory::all()->sortBy('name');

        return view('library.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeLibraryManagement();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'library_category_id' => 'nullable|integer|exists:library_categories,id',
        ]);

        $book = LibraryBook::create($validated);

        return redirect()->route('library.books.show', $book)
            ->with('success', 'Book catalogue record created successfully.');
    }

    public function show(LibraryBook $book)
    {
        $this->authorizeLibraryManagement();

        $book->load([
            'library_category',
            'library_book_copies.latestIssue.user',
            'library_book_copies.latestIssue.libraryReturn',
        ]);

        $copies = $book->library_book_copies()->latest('id')->paginate(20);

        // Recent issues through all copies of this book
        $recentIssues = $book->library_issues()
            ->with(['library_book_copy', 'user', 'libraryReturn'])
            ->latest('id')
            ->take(10)
            ->get();

        return view('library.books.show', compact('book', 'copies', 'recentIssues'));
    }

    public function edit(LibraryBook $book)
    {
        $this->authorizeLibraryManagement();
        $this->syncKhmerCategories();

        $categories = LibraryCategory::all()->sortBy('name');

        return view('library.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, LibraryBook $book)
    {
        $this->authorizeLibraryManagement();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'library_category_id' => 'nullable|integer|exists:library_categories,id',
        ]);

        $book->update($validated);

        return redirect()->route('library.books.show', $book)
            ->with('success', 'Book catalogue record updated successfully.');
    }

    public function destroy(LibraryBook $book)
    {
        $this->authorizeLibraryManagement();

        DB::transaction(function () use ($book) {
            $copyIds = DB::table('library_book_copies')
                ->where('library_book_id', $book->id)
                ->pluck('id')
                ->toArray();

            if (! empty($copyIds)) {
                $issueIds = DB::table('library_issues')
                    ->whereIn('library_book_copy_id', $copyIds)
                    ->pluck('id')
                    ->toArray();

                if (! empty($issueIds)) {
                    DB::table('library_returns')->whereIn('library_issue_id', $issueIds)->delete();
                    DB::table('library_issues')->whereIn('id', $issueIds)->delete();
                }

                DB::table('library_book_copies')->whereIn('id', $copyIds)->delete();
            }

            $book->forceDelete();
        });

        return redirect()->route('library.books.index')
            ->with('success', 'សៀវភៅត្រូវបានលុបចេញដោយជោគជ័យ។');
    }

    /**
     * Permanently purge dummy and test books created during testing.
     */
    protected function cleanupTestBooks(): void
    {
        try {
            $testBooks = LibraryBook::withTrashed()
                ->where(function ($q) {
                    $q->where('title', 'like', '%6aa55%')
                        ->orWhere('title', 'like', 'Popular Algorithms%')
                        ->orWhere('title', 'like', 'Damaged Return Book%')
                        ->orWhere('title', 'like', 'Overdue Book%')
                        ->orWhere('title', 'like', 'Double Return Book%')
                        ->orWhere('title', 'like', 'Returnable Book%')
                        ->orWhere('title', 'like', 'Book 6aa55%')
                        ->orWhere('title', 'like', 'Test Book%')
                        ->orWhere('title', 'like', 'Sample Book%')
                        ->orWhere('title', 'like', 'Book %');
                })
                ->get()
                ->filter(function ($book) {
                    return preg_match('/[0-9a-f]{13}$/', (string) $book->title)
                        || str_starts_with($book->title, 'Popular Algorithms')
                        || str_starts_with($book->title, 'Damaged Return Book')
                        || str_starts_with($book->title, 'Overdue Book')
                        || str_starts_with($book->title, 'Double Return Book')
                        || str_starts_with($book->title, 'Returnable Book')
                        || str_contains($book->title, '6aa55')
                        || str_starts_with($book->title, 'Test Book')
                        || str_starts_with($book->title, 'Sample Book');
                });

            if ($testBooks->isEmpty()) {
                return;
            }

            DB::transaction(function () use ($testBooks) {
                foreach ($testBooks as $book) {
                    $copyIds = DB::table('library_book_copies')
                        ->where('library_book_id', $book->id)
                        ->pluck('id')
                        ->toArray();

                    if (! empty($copyIds)) {
                        $issueIds = DB::table('library_issues')
                            ->whereIn('library_book_copy_id', $copyIds)
                            ->pluck('id')
                            ->toArray();

                        if (! empty($issueIds)) {
                            DB::table('library_returns')->whereIn('library_issue_id', $issueIds)->delete();
                            DB::table('library_issues')->whereIn('id', $issueIds)->delete();
                        }

                        DB::table('library_book_copies')->whereIn('id', $copyIds)->delete();
                    }

                    $book->forceDelete();
                }
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Normalize English category names to Khmer in database and clean up junk test categories without books.
     */
    protected function syncKhmerCategories(): void
    {
        try {
            $map = [
                'Science & Technology' => 'វិទ្យាសាស្ត្រ និងបច្ចេកវិទ្យា',
                'Mathematics' => 'គណិតវិទ្យា',
                'Literature' => 'អក្សរសាស្ត្រ និងអក្សរសិល្ប៍',
                'History & Geography' => 'ប្រវត្តិសាស្ត្រ និងភូមិវិទ្យា',
                'Religion & Ethics' => 'សាសនា និងសីលធម៌',
                'Language & Grammar' => 'ភាសា និងវេយ្យាករណ៍',
                'Reference Books' => 'សៀវភៅឯកសារយោង',
                "Children's Books" => 'សៀវភៅកុមារ',
                'Magazine & Journals' => 'ទស្សនាវដ្តី និងព្រឹត្តិបត្រ',
                'Sports & Health' => 'កីឡា និងសុខភាព',
                'Computer Science Test' => 'វិទ្យាសាស្ត្រកុំព្យូទ័រ',
            ];

            foreach ($map as $en => $kh) {
                DB::table('library_categories')->where('name', $en)->update(['name' => $kh]);
            }

            // Clean up junk test categories that have no books attached
            DB::table('library_categories')
                ->where(function ($q) {
                    $q->where('name', 'like', 'Category With Book%')
                        ->orWhere('name', 'like', 'New Test Category%')
                        ->orWhere('name', 'like', 'Updated Name%');
                })
                ->whereNotExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('library_books')
                        ->whereColumn('library_books.library_category_id', 'library_categories.id');
                })
                ->delete();

            // Any remaining test categories with books get clean Khmer names
            DB::table('library_categories')->where('name', 'like', 'Category With Book%')->update(['name' => 'ប្រភេទសៀវភៅទូទៅ']);
            DB::table('library_categories')->where('name', 'like', 'New Test Category%')->update(['name' => 'ប្រភេទសៀវភៅថ្មី']);
            DB::table('library_categories')->where('name', 'like', 'Updated Name%')->update(['name' => 'ប្រភេទសៀវភៅកែប្រែ']);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
