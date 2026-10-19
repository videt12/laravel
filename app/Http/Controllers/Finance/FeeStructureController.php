<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Core\AcademicYear;
use App\Models\Finance\FeeCategory;
use App\Models\Finance\FeeStructure;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    use AuthorizesFinanceAccess;

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $query = FeeStructure::with(['fee_category', 'academic_year', 'class.grade', 'class.section'])
            ->withCount('student_invoices');

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->input('academic_year_id'));
        }

        if ($request->filled('fee_category_id')) {
            $query->where('fee_category_id', $request->input('fee_category_id'));
        }

        if ($request->filled('class_id')) {
            if ($request->input('class_id') === 'institution') {
                $query->whereNull('class_id');
            } else {
                $query->where('class_id', $request->input('class_id'));
            }
        }

        $feeStructures = $query->orderByDesc('id')->paginate(15)->withQueryString();

        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $feeCategories = FeeCategory::orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->orderBy('id')->get();

        return view('finance.fee_structures.index', compact('feeStructures', 'academicYears', 'feeCategories', 'classes'));
    }

    public function create()
    {
        $this->authorizeFinanceAccess();

        $feeCategories = FeeCategory::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $classes = SchoolClass::with(['grade', 'section'])->orderBy('id')->get();

        return view('finance.fee_structures.create', compact('feeCategories', 'academicYears', 'classes'));
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'fee_category_id' => ['required', 'integer', 'exists:fee_categories,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        // Duplicate prevention for (fee_category_id, academic_year_id, class_id)
        $duplicateQuery = FeeStructure::where('fee_category_id', $validated['fee_category_id'])
            ->where('academic_year_id', $validated['academic_year_id']);

        if (! empty($validated['class_id'])) {
            $duplicateQuery->where('class_id', $validated['class_id']);
        } else {
            $duplicateQuery->whereNull('class_id');
            $validated['class_id'] = null;
        }

        if ($duplicateQuery->exists()) {
            return back()
                ->withErrors(['fee_category_id' => 'A fee structure for this fee category, academic year, and class scope already exists.'])
                ->withInput();
        }

        FeeStructure::create($validated);

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee Structure created successfully.');
    }

    public function edit(FeeStructure $feeStructure)
    {
        $this->authorizeFinanceAccess();

        $feeCategories = FeeCategory::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $classes = SchoolClass::with(['grade', 'section'])->orderBy('id')->get();

        return view('finance.fee_structures.edit', compact('feeStructure', 'feeCategories', 'academicYears', 'classes'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'fee_category_id' => ['required', 'integer', 'exists:fee_categories,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $duplicateQuery = FeeStructure::where('fee_category_id', $validated['fee_category_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('id', '!=', $feeStructure->id);

        if (! empty($validated['class_id'])) {
            $duplicateQuery->where('class_id', $validated['class_id']);
        } else {
            $duplicateQuery->whereNull('class_id');
            $validated['class_id'] = null;
        }

        if ($duplicateQuery->exists()) {
            return back()
                ->withErrors(['fee_category_id' => 'A fee structure for this fee category, academic year, and class scope already exists.'])
                ->withInput();
        }

        $feeStructure->update($validated);

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee Structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $this->authorizeFinanceAccess();

        if ($feeStructure->student_invoices()->exists()) {
            return back()->with('error', 'Cannot delete Fee Structure because invoices are currently linked to it.');
        }

        $feeStructure->delete();

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee Structure deleted successfully.');
    }
}
