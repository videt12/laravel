<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    use AuthorizesFinanceAccess;

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $query = PaymentMethod::withCount('student_payments');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        }

        $paymentMethods = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('finance.payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $this->authorizeFinanceAccess();

        return view('finance.payment_methods.create');
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:payment_methods,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment Method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        $this->authorizeFinanceAccess();

        return view('finance.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')->ignore($paymentMethod->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment Method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $this->authorizeFinanceAccess();

        if ($paymentMethod->student_payments()->exists()) {
            return back()->with('error', 'Cannot delete Payment Method because existing student payments are linked to it.');
        }

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment Method deleted successfully.');
    }
}
