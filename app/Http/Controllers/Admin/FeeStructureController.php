<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\GradeLevel;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeStructureController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $structures = FeeStructure::with(['campus', 'category'])
            ->where('school_id', $user->school_id)
            ->get();
        
        $categories = FeeCategory::where('school_id', $user->school_id)->where('is_active', true)->get();
        $gradeLevels = GradeLevel::where('school_id', $user->school_id)->where('status', 'Active')->orderBy('id', 'asc')->get();
        
        return view('admin.fees.structures.index', compact('structures', 'categories', 'gradeLevels'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Strip commas for internal numeric validation
        if ($request->has('amount')) {
            $request->merge(['amount' => str_replace(',', '', $request->amount)]);
        }
        if ($request->has('fine_amount')) {
            $request->merge(['fine_amount' => str_replace(',', '', $request->fine_amount)]);
        }

        $request->validate([
            'fee_category_id' => 'required|exists:fee_categories,id',
            'class_name' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'due_day' => 'required|integer|between:1,31',
            'fine_amount' => 'nullable|numeric|min:0',
        ]);

        FeeStructure::updateOrCreate(
            [
                'school_id' => $user->school_id,
                'fee_category_id' => $request->fee_category_id,
                'class_name' => $request->class_name,
                // campus_id is now NULL by default (Standardized)
            ],
            [
                'amount' => $request->amount,
                'due_day' => $request->due_day,
                'fine_type' => $request->fine_type ?? 'Fixed',
                'fine_amount' => $request->fine_amount ?? 0,
            ]
        );

        return back()->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $user = Auth::user();
        if ($feeStructure->school_id != $user->school_id) abort(403);
        
        $feeStructure->delete();
        return back()->with('success', 'Fee structure entry removed.');
    }
}
