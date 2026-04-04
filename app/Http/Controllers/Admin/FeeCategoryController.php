<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $categories = FeeCategory::where('school_id', $user->school_id)->get();
        return view('admin.fees.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate(['name' => 'required|string|max:255']);

        FeeCategory::create([
            'school_id' => $user->school_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Fee Category created successfully.');
    }

    public function update(Request $request, FeeCategory $feeCategory)
    {
        $user = Auth::user();
        if ($feeCategory->school_id != $user->school_id) abort(403);

        $request->validate(['name' => 'required|string|max:255']);
        $feeCategory->update($request->all());

        return back()->with('success', 'Fee Category updated.');
    }

    public function destroy(FeeCategory $feeCategory)
    {
        $user = Auth::user();
        if ($feeCategory->school_id != $user->school_id) abort(403);
        
        $feeCategory->delete();
        return back()->with('success', 'Fee Category deleted.');
    }
}
