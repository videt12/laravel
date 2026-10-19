<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Core\AcademicYear;
use App\Models\Finance\StudentInvoice;
use App\Models\Finance\StudentPayment;
use App\Models\Students\Student;
use Illuminate\Http\Request;

class StudentBalanceController extends Controller
{
    use AuthorizesFinanceAccess;

    /**
     * Display the Student Balances and Receivables report.
     */
    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $search = $request->input('search');
        $filterAcademicYear = $request->input('academic_year_id');
        $filterClass = $request->input('class_id');
        $filterSection = $request->input('section_id');
        $filterStudentType = $request->input('student_type'); // 'new', 'returning'
        $filterPaymentStatus = $request->input('payment_status'); // 'paid', 'partial', 'unpaid', 'overdue'

        // Determine context academic year
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $defaultActiveYear = $academicYears->firstWhere('is_active', 1) ?? $academicYears->first();
        $selectedYearId = $filterAcademicYear ? (int) $filterAcademicYear : $defaultActiveYear?->id;
        $selectedYear = $academicYears->firstWhere('id', $selectedYearId);

        $today = now()->toDateString();

        $query = Student::query()
            ->whereHas('student_invoices', function ($sq) {
                $sq->where('status', '!=', 'cancelled');
            })
            ->with([
                'user',
                'studentProfile',
                'studentStatus',
                'academicYear',
                'schoolClass.grade',
                'schoolClass.section',
                'section',
                'enrollments.academic_year',
            ])
            ->withSum([
                'student_invoices as total_invoiced' => function ($invQ) use ($selectedYearId) {
                    $invQ->where('status', '!=', 'cancelled')
                        ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                            $sq->where(function ($sub) use ($selectedYearId) {
                                $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                                    ->orWhereNull('fee_structure_id');
                            });
                        });
                },
            ], 'net_amount')
            ->withSum([
                'student_payments as total_paid' => function ($payQ) use ($selectedYearId) {
                    $payQ->whereHas('student_invoice', function ($invQ) use ($selectedYearId) {
                        $invQ->where('status', '!=', 'cancelled')
                            ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                                $sq->where(function ($sub) use ($selectedYearId) {
                                    $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                                        ->orWhereNull('fee_structure_id');
                                });
                            });
                    });
                },
            ], 'amount')
            ->withExists([
                'student_invoices as has_overdue' => function ($invQ) use ($today, $selectedYearId) {
                    $invQ->where('status', '!=', 'cancelled')
                        ->where('status', '!=', 'paid')
                        ->whereDate('due_date', '<', $today)
                        ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                            $sq->where(function ($sub) use ($selectedYearId) {
                                $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                                    ->orWhereNull('fee_structure_id');
                            });
                        });
                },
            ]);

        // Search Filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                    ->orWhere('roll_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Academic Year Filter
        if ($filterAcademicYear) {
            $query->where(function ($q) use ($filterAcademicYear) {
                $q->where('academic_year_id', $filterAcademicYear)
                    ->orWhereHas('enrollments', fn ($eq) => $eq->where('academic_year_id', $filterAcademicYear));
            });
        }

        // Class Filter
        if ($filterClass) {
            $query->where(function ($q) use ($filterClass, $selectedYearId) {
                $q->where('school_class_id', $filterClass)
                    ->orWhereHas('enrollments', function ($eq) use ($filterClass, $selectedYearId) {
                        $eq->when($selectedYearId, fn ($yq) => $yq->where('academic_year_id', $selectedYearId))
                            ->whereHas('enrollmentDetail', fn ($ed) => $ed->where('class_id', $filterClass));
                    });
            });
        }

        // Section Filter
        if ($filterSection) {
            $query->where(function ($q) use ($filterSection) {
                $q->where('section_id', $filterSection)
                    ->orWhereHas('schoolClass', fn ($cq) => $cq->where('section_id', $filterSection));
            });
        }

        // New / Returning Filter
        if ($filterStudentType && $selectedYearId) {
            if ($filterStudentType === 'new') {
                $query->whereNewStudent($selectedYearId);
            } elseif ($filterStudentType === 'returning') {
                $query->whereReturningStudent($selectedYearId);
            }
        }

        // Payment Status Filter (using indexed status on student_invoices)
        if ($filterPaymentStatus) {
            $invoiceYearScope = function ($sq) use ($selectedYearId) {
                $sq->when($selectedYearId, function ($sub) use ($selectedYearId) {
                    $sub->where(function ($yq) use ($selectedYearId) {
                        $yq->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                            ->orWhereNull('fee_structure_id');
                    });
                });
            };

            if ($filterPaymentStatus === 'paid') {
                $query->whereHas('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->where('status', 'paid');
                    $invoiceYearScope($q);
                })->whereDoesntHave('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->whereNotIn('status', ['paid', 'cancelled']);
                    $invoiceYearScope($q);
                });
            } elseif ($filterPaymentStatus === 'partial') {
                $query->whereHas('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->where('status', 'partial');
                    $invoiceYearScope($q);
                });
            } elseif ($filterPaymentStatus === 'unpaid') {
                $query->whereHas('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->where('status', 'unpaid');
                    $invoiceYearScope($q);
                })->whereDoesntHave('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->whereIn('status', ['partial', 'paid']);
                    $invoiceYearScope($q);
                });
            } elseif ($filterPaymentStatus === 'overdue') {
                $query->whereHas('student_invoices', function ($q) use ($today, $invoiceYearScope) {
                    $q->whereNotIn('status', ['paid', 'cancelled'])
                        ->whereDate('due_date', '<', $today);
                    $invoiceYearScope($q);
                });
            }
        }

        // Calculate Summary Metrics respecting filtered query
        $summaryQuery = clone $query;
        $summaryStudents = (clone $summaryQuery)->get();
        $totalStudentsWithInvoices = $summaryStudents->count();

        $filteredStudentIds = $summaryStudents->pluck('id');
        $totalInvoicedSum = StudentInvoice::whereIn('student_id', $filteredStudentIds)
            ->where('status', '!=', 'cancelled')
            ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                $sq->where(function ($sub) use ($selectedYearId) {
                    $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                        ->orWhereNull('fee_structure_id');
                });
            })
            ->sum('net_amount');

        $totalPaidSum = StudentPayment::whereHas('student_invoice', function ($invQ) use ($filteredStudentIds, $selectedYearId) {
            $invQ->whereIn('student_id', $filteredStudentIds)
                ->where('status', '!=', 'cancelled')
                ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                    $sq->where(function ($sub) use ($selectedYearId) {
                        $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                            ->orWhereNull('fee_structure_id');
                    });
                });
        })->sum('amount');

        $totalOutstanding = max(0.0, round((float) $totalInvoicedSum - (float) $totalPaidSum, 2));

        $paidCount = 0;
        $partialCount = 0;
        $unpaidCount = 0;
        $overdueCount = 0;

        foreach ($summaryStudents as $st) {
            $inv = (float) ($st->total_invoiced ?? 0);
            $pd = (float) ($st->total_paid ?? 0);

            if ($st->has_overdue) {
                $overdueCount++;
            }

            if ($inv > 0 && $pd >= $inv) {
                $paidCount++;
            } elseif ($pd > 0) {
                $partialCount++;
            } else {
                $unpaidCount++;
            }
        }

        // CSV Export Support
        if ($request->input('export') === 'csv') {
            $filename = 'student-balances-'.now()->format('Y-m-d').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($summaryStudents) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Student', 'Admission No', 'Class', 'Section', 'Academic Year', 'Type', 'Total Invoiced ($)', 'Total Paid ($)', 'Balance ($)', 'Status']);

                foreach ($summaryStudents as $student) {
                    $enrollment = $student->enrollments->first();
                    $inv = (float) ($student->total_invoiced ?? 0);
                    $pd = (float) ($student->total_paid ?? 0);
                    $bal = max(0.0, round($inv - $pd, 2));
                    $contextYearId = $filterAcademicYear ? (int) $filterAcademicYear : null;
                    $type = ucfirst($student->getStudentTypeForYear($contextYearId));

                    fputcsv($handle, [
                        $student->user?->name ?? 'N/A',
                        $student->admission_number,
                        $enrollment?->classes?->name ?? $student->schoolClass?->name ?? 'N/A',
                        $enrollment?->section?->name ?? $student->section?->name ?? '',
                        $enrollment?->academicYear?->name ?? $student->academicYear?->name ?? '',
                        $type,
                        number_format($inv, 2, '.', ''),
                        number_format($pd, 2, '.', ''),
                        number_format($bal, 2, '.', ''),
                        $status,
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Paginate results
        $students = $query->latest('students.id')
            ->paginate(15)
            ->withQueryString();

        // Process dynamic attributes on the paginated slice
        foreach ($students as $student) {
            $invoiced = (float) ($student->total_invoiced ?? 0);
            $paid = (float) ($student->total_paid ?? 0);
            $balance = max(0.0, round($invoiced - $paid, 2));

            if ($student->has_overdue) {
                $pStatus = 'overdue';
            } elseif ($invoiced > 0 && $paid >= $invoiced) {
                $pStatus = 'paid';
            } elseif ($paid > 0) {
                $pStatus = 'partial';
            } else {
                $pStatus = 'unpaid';
            }

            $student->calculated_invoiced = $invoiced;
            $student->calculated_paid = $paid;
            $student->calculated_balance = $balance;
            $student->calculated_payment_status = $pStatus;
            $contextYearId = $filterAcademicYear ? (int) $filterAcademicYear : null;
            $student->calculated_student_type = $student->getStudentTypeForYear($contextYearId);
        }

        $classes = SchoolClass::with(['grade', 'section'])->get();
        $sections = Section::orderBy('name')->get();

        $hasFilters = $search || $filterAcademicYear || $filterClass || $filterSection || $filterStudentType || $filterPaymentStatus;

        $totalStudents = $totalStudentsWithInvoices;
        $totalInvoiced = $totalInvoicedSum;
        $totalCollected = $totalPaidSum;

        return view('finance.balances.index', compact(
            'students',
            'search',
            'filterAcademicYear',
            'filterClass',
            'filterSection',
            'filterStudentType',
            'filterPaymentStatus',
            'selectedYearId',
            'academicYears',
            'classes',
            'sections',
            'hasFilters',
            'totalStudents',
            'totalStudentsWithInvoices',
            'paidCount',
            'partialCount',
            'unpaidCount',
            'overdueCount',
            'totalOutstanding',
            'totalInvoiced',
            'totalInvoicedSum',
            'totalCollected',
            'totalPaidSum'
        ));
    }
}
