<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;
        
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay() 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->endOfDay() 
            : Carbon::now()->endOfDay();

        $query = Payment::with(['invoice.student', 'collector'])
            ->where('school_id', $schoolId)
            ->whereBetween('paid_at', [$startDate, $endDate]);

        if ($request->filled('campus_id')) {
            $query->where('campus_id', $request->campus_id);
        }

        $payments = $query->latest()->paginate(50);
        
        $totalCollected = (clone $query)->sum('amount');
        $dailyAverages = $payments->groupBy(fn($p) => $p->paid_at->toDateString())
            ->map(fn($day) => $day->sum('amount'));

        return view('admin.fees.reports.collection', compact('payments', 'totalCollected', 'startDate', 'endDate'));
    }
}
