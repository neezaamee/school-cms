<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Invoice;
use App\Models\Student;
use App\Services\FeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeInvoiceController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Invoice::with(['student', 'campus'])->where('school_id', $user->school_id);

        if ($request->filled('campus_id')) {
            $query->where('campus_id', $request->campus_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('psid')) {
            $query->where('psid', 'LIKE', "%{$request->psid}%");
        }

        $invoices = $query->latest()->paginate(20);
        $campuses = Campus::where('school_id', $user->school_id)->get();

        return view('admin.fees.invoices.index', compact('invoices', 'campuses'));
    }

    /**
     * Show form to generate bulk invoices.
     */
    public function create()
    {
        $user = Auth::user();
        $campuses = Campus::where('school_id', $user->school_id)->get();
        return view('admin.fees.invoices.create', compact('campuses'));
    }

    /**
     * Bulk Generate Invoices for a class/campus.
     */
    public function generate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'class_name' => 'required|string',
            'month' => 'required',
            'year' => 'required|integer',
        ]);

        $count = $this->feeService->generateMonthlyInvoices(
            $user->school_id,
            $request->campus_id,
            $request->class_name,
            $request->month,
            $request->year
        );

        return redirect()->route('admin.invoices.index')
            ->with('success', "Batch processing complete. Generated {$count} new invoices.");
    }

    public function show(Invoice $invoice)
    {
        $user = Auth::user();
        if ($invoice->school_id != $user->school_id) abort(403);
        
        $invoice->load(['student', 'items.category', 'payments.collector']);
        return view('admin.fees.invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $user = Auth::user();
        if ($invoice->school_id != $user->school_id) abort(403);
        
        $invoice->load(['student', 'items.category', 'school']);
        return view('admin.fees.invoices.print', compact('invoice'));
    }
}
