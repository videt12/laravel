<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LibraryCategoryController extends Controller
{
    use AuthorizesLibraryAccess;

    public function index(Request $request)
    {
        $this->authorizeLibraryManagement();
        $this->syncKhmerCategories();

        $search = $request->query('search');

        $categories = LibraryCategory::withCount('library_books')
            ->when($search, function ($query, $term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('library.categories.index', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $this->authorizeLibraryManagement();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:library_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.unique' => 'ប្រភេទសៀវភៅ «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះប្រភេទសៀវភៅ។',
        ]);

        LibraryCategory::create($validated);

        return redirect()->route('library.categories.index')
            ->with('success', 'ប្រភេទសៀវភៅត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    public function update(Request $request, LibraryCategory $category)
    {
        $this->authorizeLibraryManagement();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('library_categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.unique' => 'ប្រភេទសៀវភៅ «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះប្រភេទសៀវភៅ។',
        ]);

        $category->update($validated);

        return redirect()->route('library.categories.index')
            ->with('success', 'ប្រភេទសៀវភៅត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    public function destroy(LibraryCategory $category)
    {
        $this->authorizeLibraryManagement();

        if ($category->library_books()->exists()) {
            return redirect()->route('library.categories.index')
                ->with('error', 'Cannot delete category containing books.');
        }

        $category->delete();

        return redirect()->route('library.categories.index')
            ->with('success', 'Book category deleted successfully.');
    }

    /**
     * Normalize English category names to Khmer in database and clean up junk test categories without books.
     */
    protected function syncKhmerCategories(): void
    {
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
    }
}
