<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\FeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeePaymentController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $invoice = null;

        if ($request->filled('search')) {
            $search = $request->search;
            $invoice = Invoice::with(['student', 'items.category'])
                ->where('school_id', $user->school_id)
                ->where(function($q) use ($search) {
                    $q->where('psid', $search)
                      ->orWhereHas('student', function($sq) use ($search) {
                          $sq->where('admission_no', $search)
                            ->orWhere('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%");
                      });
                })
                ->where('status', '!=', 'Paid')
                ->first();
            
            if (!$invoice && strlen($search) >= 3) {
                session()->now('error', 'No unpaid invoice found for this query.');
            }
        }

        $recentPayments = Payment::with(['invoice.student', 'collector'])
            ->where('school_id', $user->school_id)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.fees.payments.index', compact('invoice', 'recentPayments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        if ($invoice->school_id != $user->school_id) abort(403);

        if ($request->amount > $invoice->balance) {
            return back()->with('error', 'Payment amount exceeds current balance.');
        }

        $this->feeService->processCashPayment(
            $invoice,
            $request->amount,
            $user->id,
            $request->transaction_id
        );

        return redirect()->route('admin.payments.index')
            ->with('success', "Payment of Rs. {$request->amount} recorded for {$invoice->student->full_name}.");
    }
}
