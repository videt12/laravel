<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Core\AcademicYear;
use App\Models\Finance\FeeCategory;
use App\Models\Finance\FeeStructure;
use App\Models\Finance\InvoiceDetail;
use App\Models\Finance\PaymentMethod;
use App\Models\Finance\StudentInvoice;
use App\Models\Finance\StudentPayment;
use App\Models\Students\Student;
use App\Services\Finance\KhmerNumberSpeller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentInvoiceController extends Controller
{
    use AuthorizesFinanceAccess;

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $today = now()->toDateString();

        $query = StudentInvoice::with([
            'student.user',
            'student.schoolClass.grade',
            'student.schoolClass.section',
            'student.academicYear',
            'fee_structure.fee_category',
            'fee_structure.academic_year',
            'student_payments',
        ]);

        if ($request->boolean('outstanding')) {
            $query->whereIn('status', ['unpaid', 'partial']);
        } elseif ($request->filled('status')) {
            if ($request->input('status') === 'overdue') {
                $query->whereIn('status', ['unpaid', 'partial'])
                    ->whereDate('due_date', '<', $today);
            } else {
                $query->where('status', $request->input('status'));
            }
        }

        if ($request->filled('academic_year_id')) {
            $yearId = $request->input('academic_year_id');
            $query->where(function ($q) use ($yearId) {
                $q->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $yearId))
                    ->orWhereHas('student', function ($sq) use ($yearId) {
                        $sq->where('academic_year_id', $yearId)
                            ->orWhereHas('enrollments', fn ($eq) => $eq->where('academic_year_id', $yearId));
                    });
            });
        }

        if ($request->filled('class_id')) {
            $classId = $request->input('class_id');
            $query->whereHas('student', function ($sq) use ($classId) {
                $sq->where('school_class_id', $classId);
            });
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->input('student_id'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                // If search is numeric or like #INV-123, match ID
                $cleanTerm = ltrim(preg_replace('/[^0-9]/', '', $term), '0');
                if (! empty($cleanTerm)) {
                    $q->where('id', $cleanTerm);
                }
                $q->orWhereHas('student', function ($sq) use ($term) {
                    $sq->where('admission_number', 'like', "%{$term}%")
                        ->orWhere('roll_number', 'like', "%{$term}%")
                        ->orWhereHas('user', function ($uq) use ($term) {
                            $uq->where('name', 'like', "%{$term}%");
                        });
                });
            });
        }

        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->input('due_date_from'));
        }

        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->input('due_date_to'));
        }

        // Summary metrics for current query
        $totalInvoicesCount = (clone $query)->count();
        $totalInvoicedAmount = (float) (clone $query)->where('status', '!=', 'cancelled')->sum('net_amount');
        $validInvoiceIdsQuery = (clone $query)->where('status', '!=', 'cancelled')->select('id');
        $totalCollectedAmount = (float) StudentPayment::whereIn('student_invoice_id', $validInvoiceIdsQuery)->sum('amount');
        $totalOutstandingAmount = max(0.0, round($totalInvoicedAmount - $totalCollectedAmount, 2));

        // CSV Export Support
        if ($request->input('export') === 'csv') {
            $filename = 'student-invoices-'.now()->format('Y-m-d').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $exportInvoices = (clone $query)->orderByDesc('id')->get();

            $callback = function () use ($exportInvoices) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'Invoice #',
                    'Student',
                    'Admission No',
                    'Class',
                    'Academic Year',
                    'Due Date',
                    'Net Amount ($)',
                    'Paid ($)',
                    'Balance ($)',
                    'Status',
                ]);

                foreach ($exportInvoices as $inv) {
                    $academicYearName = $inv->fee_structure?->academic_year?->name ?? $inv->student?->academicYear?->name ?? '—';
                    $status = ($inv->due_date && $inv->due_date->isPast() && in_array($inv->status, ['unpaid', 'partial'])) ? 'Overdue' : ucfirst($inv->status);

                    fputcsv($handle, [
                        $inv->invoice_number,
                        $inv->student?->user?->name ?? 'N/A',
                        $inv->student?->admission_number ?? 'N/A',
                        $inv->student?->schoolClass?->name ?? '—',
                        $academicYearName,
                        $inv->due_date?->format('Y-m-d') ?? '',
                        number_format($inv->net_amount, 2, '.', ''),
                        number_format($inv->total_paid, 2, '.', ''),
                        number_format($inv->balance, 2, '.', ''),
                        $status,
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        $invoices = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $classes = SchoolClass::with('grade')->get();

        return view('finance.invoices.index', compact(
            'invoices',
            'academicYears',
            'classes',
            'totalInvoicesCount',
            'totalInvoicedAmount',
            'totalCollectedAmount',
            'totalOutstandingAmount'
        ));
    }

    public function create(Request $request)
    {
        $this->authorizeFinanceAccess();

        $students = Student::with(['user', 'schoolClass.grade', 'schoolClass.section'])
            ->orderBy('id')
            ->limit(100)
            ->get();

        $feeCategories = FeeCategory::orderBy('name')->get();
        $feeStructures = FeeStructure::with(['fee_category', 'class.grade', 'class.section', 'academic_year'])
            ->orderByDesc('id')
            ->get();

        $selectedStudentId = $request->input('student_id');

        return view('finance.invoices.create', compact('students', 'feeCategories', 'feeStructures', 'selectedStudentId'));
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'fee_structure_id' => ['nullable', 'integer', 'exists:fee_structures,id'],
            'due_date' => ['required', 'date'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.fee_category_id' => ['required', 'integer', 'exists:fee_categories,id'],
            'items.*.amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $discountAmount = round((float) ($validated['discount_amount'] ?? 0.0), 2);

        // Sum line items
        $calculatedTotal = 0.0;
        foreach ($validated['items'] as $item) {
            $calculatedTotal += round((float) $item['amount'], 2);
        }
        $calculatedTotal = round($calculatedTotal, 2);

        if ($discountAmount > $calculatedTotal) {
            return back()
                ->withErrors(['discount_amount' => 'Discount amount cannot exceed the total amount of invoice line items.'])
                ->withInput();
        }

        $netAmount = round($calculatedTotal - $discountAmount, 2);

        $invoice = DB::transaction(function () use ($validated, $calculatedTotal, $discountAmount, $netAmount) {
            $invoice = StudentInvoice::create([
                'student_id' => $validated['student_id'],
                'fee_structure_id' => $validated['fee_structure_id'] ?? null,
                'total_amount' => $calculatedTotal,
                'discount_amount' => $discountAmount,
                'net_amount' => $netAmount,
                'status' => 'unpaid',
                'due_date' => $validated['due_date'],
            ]);

            foreach ($validated['items'] as $item) {
                InvoiceDetail::create([
                    'student_invoice_id' => $invoice->id,
                    'fee_category_id' => $item['fee_category_id'],
                    'amount' => round((float) $item['amount'], 2),
                ]);
            }

            return $invoice;
        });

        return redirect()->route('student-invoices.show', $invoice->id)
            ->with('success', "Invoice {$invoice->invoice_number} created successfully.");
    }

    public function show(StudentInvoice $studentInvoice)
    {
        $this->authorizeFinanceAccess();

        $studentInvoice->load([
            'student.user',
            'student.schoolClass.grade',
            'student.schoolClass.section',
            'student.academicYear',
            'fee_structure.fee_category',
            'fee_structure.academic_year',
            'invoice_details.fee_category',
            'student_payments.payment_method',
        ]);

        $paymentMethods = PaymentMethod::orderBy('name')->get();

        return view('finance.invoices.show', compact('studentInvoice', 'paymentMethods'));
    }

    public function edit(StudentInvoice $studentInvoice)
    {
        $this->authorizeFinanceAccess();

        if ($studentInvoice->student_payments()->exists()) {
            return redirect()->route('student-invoices.show', $studentInvoice->id)
                ->with('error', 'Cannot edit an invoice that already has recorded payments.');
        }

        if ($studentInvoice->status === 'cancelled') {
            return redirect()->route('student-invoices.show', $studentInvoice->id)
                ->with('error', 'Cannot edit a cancelled invoice.');
        }

        $studentInvoice->load(['invoice_details', 'student.user']);
        $feeCategories = FeeCategory::orderBy('name')->get();
        $feeStructures = FeeStructure::with(['fee_category', 'class', 'academic_year'])->get();

        return view('finance.invoices.edit', compact('studentInvoice', 'feeCategories', 'feeStructures'));
    }

    public function update(Request $request, StudentInvoice $studentInvoice)
    {
        $this->authorizeFinanceAccess();

        if ($studentInvoice->student_payments()->exists()) {
            return back()->with('error', 'Cannot update an invoice that already has recorded payments.');
        }

        if ($studentInvoice->status === 'cancelled') {
            return back()->with('error', 'Cannot update a cancelled invoice.');
        }

        $validated = $request->validate([
            'fee_structure_id' => ['nullable', 'integer', 'exists:fee_structures,id'],
            'due_date' => ['required', 'date'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.fee_category_id' => ['required', 'integer', 'exists:fee_categories,id'],
            'items.*.amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $discountAmount = round((float) ($validated['discount_amount'] ?? 0.0), 2);

        $calculatedTotal = 0.0;
        foreach ($validated['items'] as $item) {
            $calculatedTotal += round((float) $item['amount'], 2);
        }
        $calculatedTotal = round($calculatedTotal, 2);

        if ($discountAmount > $calculatedTotal) {
            return back()
                ->withErrors(['discount_amount' => 'Discount amount cannot exceed the total amount of invoice line items.'])
                ->withInput();
        }

        $netAmount = round($calculatedTotal - $discountAmount, 2);

        DB::transaction(function () use ($studentInvoice, $validated, $calculatedTotal, $discountAmount, $netAmount) {
            $studentInvoice->update([
                'fee_structure_id' => $validated['fee_structure_id'] ?? null,
                'total_amount' => $calculatedTotal,
                'discount_amount' => $discountAmount,
                'net_amount' => $netAmount,
                'due_date' => $validated['due_date'],
            ]);

            // Replace line items
            $studentInvoice->invoice_details()->delete();

            foreach ($validated['items'] as $item) {
                InvoiceDetail::create([
                    'student_invoice_id' => $studentInvoice->id,
                    'fee_category_id' => $item['fee_category_id'],
                    'amount' => round((float) $item['amount'], 2),
                ]);
            }
        });

        return redirect()->route('student-invoices.show', $studentInvoice->id)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(StudentInvoice $studentInvoice)
    {
        $this->authorizeFinanceAccess();

        // Safe deletion rule: Block if invoice has recorded payments
        if ($studentInvoice->student_payments()->exists()) {
            return back()->with('error', 'Cannot delete invoice: This invoice has recorded payments attached to it.');
        }

        $studentInvoice->delete();

        return redirect()->route('student-invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function cancel(StudentInvoice $studentInvoice)
    {
        $this->authorizeFinanceAccess();

        if ($studentInvoice->student_payments()->exists()) {
            return back()->with('error', 'Cannot cancel an invoice with recorded payments.');
        }

        $studentInvoice->status = 'cancelled';
        $studentInvoice->save();

        return redirect()->route('student-invoices.show', $studentInvoice->id)
            ->with('success', "Invoice {$studentInvoice->invoice_number} has been cancelled.");
    }

    public function receipt(StudentInvoice $studentInvoice)
    {
        $this->authorizeFinanceAccess();

        $studentInvoice->load([
            'student.user',
            'student.profile',
            'student.schoolClass.grade',
            'student.schoolClass.section',
            'student.schoolShift',
            'student.academicYear',
            'fee_structure.fee_category',
            'invoice_details.fee_category',
            'student_payments.payment_method',
        ]);

        $latestPayment = $studentInvoice->student_payments()->latest('payment_date')->latest('id')->first();

        if ($latestPayment) {
            return redirect()->route('student-payments.receipt', $latestPayment->id);
        }

        // If no payment recorded yet, synthesize a statement-level payment object for display
        $mockPayment = new StudentPayment([
            'student_invoice_id' => $studentInvoice->id,
            'amount' => $studentInvoice->net_amount,
            'payment_date' => now(),
            'period_type' => 'month',
            'period_start_date' => now()->startOfMonth(),
            'period_end_date' => now()->endOfMonth(),
            'next_payment_date' => $studentInvoice->due_date ?? now()->addMonth(),
            'amount_khr' => round(((float) $studentInvoice->net_amount) * 4100, -2),
            'amount_in_words' => KhmerNumberSpeller::spellCurrency((float) $studentInvoice->net_amount, 'USD'),
            'receiver_name' => auth()->user()?->name ?? 'អ្នកទទួល',
        ]);
        $mockPayment->setRelation('student_invoice', $studentInvoice);

        return view('finance.payments.receipt', ['studentPayment' => $mockPayment]);
    }
}
