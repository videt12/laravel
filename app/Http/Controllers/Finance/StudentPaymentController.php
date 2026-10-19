<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Core\AcademicYear;
use App\Models\Finance\PaymentMethod;
use App\Models\Finance\StudentInvoice;
use App\Models\Finance\StudentPayment;
use App\Models\Students\Student;
use App\Services\Finance\KhmerNumberSpeller;
use App\Services\Telegram\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentPaymentController extends Controller
{
    use AuthorizesFinanceAccess;

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $query = StudentPayment::with([
            'student_invoice.student.user',
            'student_invoice.student.schoolClass.grade',
            'student_invoice.student.academicYear',
            'student_invoice.fee_structure.academic_year',
            'payment_method',
        ]);

        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->input('payment_method_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('student_id')) {
            $query->whereHas('student_invoice', function ($iq) use ($request) {
                $iq->where('student_id', $request->input('student_id'));
            });
        }

        if ($request->filled('academic_year_id')) {
            $yearId = $request->input('academic_year_id');
            $query->whereHas('student_invoice', function ($iq) use ($yearId) {
                $iq->where(function ($sub) use ($yearId) {
                    $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $yearId))
                        ->orWhereHas('student', function ($sq) use ($yearId) {
                            $sq->where('academic_year_id', $yearId)
                                ->orWhereHas('enrollments', fn ($eq) => $eq->where('academic_year_id', $yearId));
                        });
                });
            });
        }

        if ($request->filled('class_id')) {
            $classId = $request->input('class_id');
            $query->whereHas('student_invoice.student', function ($sq) use ($classId) {
                $sq->where('school_class_id', $classId);
            });
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $cleanTerm = ltrim(preg_replace('/[^0-9]/', '', $term), '0');

            $query->where(function ($q) use ($term, $cleanTerm) {
                if (! empty($cleanTerm)) {
                    $q->where('student_invoice_id', $cleanTerm);
                }
                $q->orWhere('transaction_id', 'like', "%{$term}%")
                    ->orWhere('receipt_number', 'like', "%{$term}%")
                    ->orWhereHas('student_invoice.student', function ($sq) use ($term) {
                        $sq->where('admission_number', 'like', "%{$term}%")
                            ->orWhereHas('user', function ($uq) use ($term) {
                                $uq->where('name', 'like', "%{$term}%");
                            });
                    });
            });
        }

        // Summary collection metrics for current filtered query
        $totalPaymentsCount = (clone $query)->count();
        $totalAmountCollected = (float) (clone $query)->sum('amount');

        // CSV Export Support
        if ($request->input('export') === 'csv') {
            $filename = 'payment-collection-'.now()->format('Y-m-d').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $exportPayments = (clone $query)->orderByDesc('payment_date')->orderByDesc('id')->get();

            $callback = function () use ($exportPayments) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'Payment Date',
                    'Receipt Number',
                    'Student',
                    'Admission No',
                    'Class',
                    'Invoice #',
                    'Amount ($)',
                    'Payment Method',
                    'Transaction Ref',
                    'Recorded By',
                ]);

                foreach ($exportPayments as $payment) {
                    $student = $payment->student_invoice?->student;
                    fputcsv($handle, [
                        $payment->payment_date?->format('Y-m-d') ?? '',
                        $payment->formatted_receipt_number,
                        $student?->user?->name ?? 'N/A',
                        $student?->admission_number ?? 'N/A',
                        $student?->schoolClass?->name ?? '—',
                        '#INV-'.$payment->student_invoice_id,
                        number_format($payment->amount, 2, '.', ''),
                        $payment->payment_method?->name ?? 'Direct Payment',
                        $payment->transaction_id ?? '—',
                        $payment->receiver_name ?? 'Staff',
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        $payments = $query->orderByDesc('payment_date')->orderByDesc('id')->paginate(15)->withQueryString();
        $paymentMethods = PaymentMethod::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $classes = SchoolClass::with('grade')->get();

        return view('finance.payments.index', compact(
            'payments',
            'paymentMethods',
            'academicYears',
            'classes',
            'totalPaymentsCount',
            'totalAmountCollected'
        ));
    }

    public function create(Request $request)
    {
        $this->authorizeFinanceAccess();

        $invoiceId = $request->input('invoice_id');
        $studentId = $request->input('student_id');
        $invoice = null;
        $selectedStudent = null;

        if ($invoiceId) {
            $invoice = StudentInvoice::with([
                'student.user',
                'student.profile',
                'student.schoolClass.grade',
                'student.schoolShift',
                'student.academicYear',
                'student_payments',
                'invoice_details.fee_category',
            ])->findOrFail($invoiceId);

            $selectedStudent = $invoice->student;
        } elseif ($studentId) {
            $selectedStudent = Student::with([
                'user',
                'studentProfile',
                'schoolClass.grade',
                'schoolShift',
                'academicYear',
            ])->find($studentId);
        }

        $paymentMethods = PaymentMethod::orderBy('name')->get();

        // Load active students who currently have unpaid or partial invoices
        $studentsWithInvoices = Student::with(['user', 'schoolClass'])
            ->whereHas('student_invoices', function ($q) {
                $q->whereIn('status', ['unpaid', 'partial']);
            })
            ->orderBy('id')
            ->limit(250)
            ->get();

        // If a student is selected or invoice is selected, load that student's payable invoices
        $studentInvoices = collect();
        if ($selectedStudent) {
            $studentInvoices = StudentInvoice::with([
                'student_payments',
                'fee_structure.academic_year',
                'fee_structure.fee_category',
                'invoice_details.fee_category',
            ])
                ->where('student_id', $selectedStudent->id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->orderBy('due_date')
                ->get();
        }

        // Keep existing $invoices collection for backwards-compatibility
        $invoices = StudentInvoice::with([
            'student.user',
            'student.profile',
            'student.schoolClass.grade',
            'student.schoolShift',
        ])
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return view('finance.payments.create', compact(
            'invoice',
            'invoices',
            'selectedStudent',
            'studentsWithInvoices',
            'studentInvoices',
            'paymentMethods'
        ));
    }

    /**
     * AJAX endpoint to fetch payable invoices for a specific student.
     */
    public function getUnpaidInvoices(Student $student)
    {
        $this->authorizeFinanceAccess();

        $invoices = StudentInvoice::with([
            'fee_structure.academic_year',
            'fee_structure.fee_category',
            'invoice_details.fee_category',
            'student_payments:id,student_invoice_id,amount',
        ])
            ->where('student_id', $student->id)
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderBy('due_date')
            ->get()
            ->map(function ($inv) {
                $totalPaid = (float) $inv->student_payments->sum('amount');
                $balance = max(0.0, round((float) $inv->net_amount - $totalPaid, 2));

                $feeTypeName = $inv->fee_structure?->fee_category?->name
                    ?? $inv->invoice_details->first()?->fee_category?->name
                    ?? 'General Fee';

                $academicYearName = $inv->fee_structure?->academic_year?->name ?? '—';

                return [
                    'id' => $inv->id,
                    'invoice_number' => $inv->invoice_number,
                    'academic_year' => $academicYearName,
                    'fee_type' => $feeTypeName,
                    'due_date' => $inv->due_date?->format('Y-m-d') ?? '—',
                    'is_overdue' => $inv->due_date && $inv->due_date->isPast() && $balance > 0,
                    'net_amount' => (float) $inv->net_amount,
                    'total_paid' => $totalPaid,
                    'balance' => $balance,
                    'status' => $inv->status,
                ];
            });

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->user?->name ?? 'Student #'.$student->id,
                'admission_number' => $student->admission_number,
            ],
            'invoices' => $invoices,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'student_invoice_id' => ['required', 'integer', 'exists:student_invoices,id'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'receipt_number' => ['nullable', 'string', 'max:50', 'unique:student_payments,receipt_number'],
            'student_type' => ['nullable', 'string', 'in:old,new'],
            'shift' => ['nullable', 'string', 'max:50'],
            'english_class_time' => ['nullable', 'string', 'max:100'],
            'book_level' => ['nullable', 'string', 'max:100'],
            'period_type' => ['nullable', 'string', 'in:month,trimester,semester,year'],
            'period_months' => ['nullable', 'integer', 'min:1', 'max:60'],
            'period_start_date' => ['nullable', 'date'],
            'period_end_date' => ['nullable', 'date'],
            'next_payment_date' => ['nullable', 'date'],
            'amount_khr' => ['nullable', 'numeric', 'min:0'],
            'amount_in_words' => ['nullable', 'string', 'max:255'],
            'receiver_name' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string'],
            'print_receipt' => ['nullable', 'boolean'],
        ]);

        $paymentAmount = round((float) $validated['amount'], 2);

        $payment = DB::transaction(function () use ($validated, $paymentAmount) {
            // Lock invoice to recalculate latest balance and prevent concurrency issues
            $invoice = StudentInvoice::lockForUpdate()->findOrFail($validated['student_invoice_id']);

            // Reject payment against cancelled or already paid invoice
            if ($invoice->status === 'cancelled') {
                abort(422, 'Cannot record payment against a cancelled invoice.');
            }

            // Recalculate remaining balance from database
            $totalPaid = (float) $invoice->student_payments()->sum('amount');
            $remainingBalance = max(0.0, round((float) $invoice->net_amount - $totalPaid, 2));

            if ($invoice->status === 'paid' || $remainingBalance <= 0) {
                abort(422, 'Cannot record payment against an already fully paid invoice.');
            }

            // Prevent overpayment
            if ($paymentAmount > $remainingBalance) {
                abort(422, 'Payment amount of $'.number_format($paymentAmount, 2).' exceeds remaining balance of $'.number_format($remainingBalance, 2).'.');
            }

            // Auto-calculate KHR and words if not supplied
            $amountKhr = isset($validated['amount_khr']) && $validated['amount_khr'] > 0
                ? round((float) $validated['amount_khr'], 2)
                : round($paymentAmount * 4100, -2);

            $amountInWords = ! empty($validated['amount_in_words'])
                ? $validated['amount_in_words']
                : KhmerNumberSpeller::spellCurrency($paymentAmount, 'USD');

            $payment = StudentPayment::create([
                'student_invoice_id' => $invoice->id,
                'payment_method_id' => $validated['payment_method_id'] ?? null,
                'amount' => $paymentAmount,
                'payment_date' => $validated['payment_date'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'receipt_number' => $validated['receipt_number'] ?? null,
                'student_type' => $validated['student_type'] ?? null,
                'shift' => $validated['shift'] ?? null,
                'english_class_time' => $validated['english_class_time'] ?? null,
                'book_level' => $validated['book_level'] ?? null,
                'period_type' => $validated['period_type'] ?? null,
                'period_months' => $validated['period_months'] ?? null,
                'period_start_date' => $validated['period_start_date'] ?? null,
                'period_end_date' => $validated['period_end_date'] ?? null,
                'next_payment_date' => $validated['next_payment_date'] ?? null,
                'amount_khr' => $amountKhr,
                'amount_in_words' => $amountInWords,
                'receiver_name' => $validated['receiver_name'] ?? (auth()->user()?->name ?? 'អ្នកទទួល'),
                'note' => $validated['note'] ?? null,
            ]);

            // Recalculate status
            $invoice->recalculateStatus();

            return $payment;
        });

        // Send Telegram alert if enabled
        try {
            app(TelegramNotificationService::class)->sendPaymentAlert($payment);
        } catch (\Throwable $e) {
            Log::warning('Telegram Payment Alert error: '.$e->getMessage());
        }

        if ($request->boolean('print_receipt')) {
            return redirect()->route('student-payments.receipt', $payment->id)
                ->with('success', 'Payment of $'.number_format($payment->amount, 2).' recorded. Ready to print receipt.');
        }

        return redirect()->route('student-invoices.show', $payment->student_invoice_id)
            ->with('success', 'Payment of $'.number_format($payment->amount, 2).' recorded successfully.');
    }

    public function receipt(StudentPayment $studentPayment)
    {
        $this->authorizeFinanceAccess();

        $studentPayment->load([
            'student_invoice.student.user',
            'student_invoice.student.profile',
            'student_invoice.student.schoolClass.grade',
            'student_invoice.student.schoolClass.section',
            'student_invoice.student.schoolShift',
            'student_invoice.student.academicYear',
            'student_invoice.fee_structure.fee_category',
            'student_invoice.invoice_details.fee_category',
            'payment_method',
        ]);

        return view('finance.payments.receipt', compact('studentPayment'));
    }

    public function destroy(StudentPayment $studentPayment)
    {
        $this->authorizeFinanceAccess();

        $invoiceId = $studentPayment->student_invoice_id;

        DB::transaction(function () use ($studentPayment) {
            $invoice = $studentPayment->student_invoice;
            $studentPayment->delete();

            // Status is also recalculated via model booted event, but ensure it here
            $invoice?->recalculateStatus();
        });

        return redirect()->route('student-invoices.show', $invoiceId)
            ->with('success', 'Payment record deleted and invoice status updated.');
    }
}
