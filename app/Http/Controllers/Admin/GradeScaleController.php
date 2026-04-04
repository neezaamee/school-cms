<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeScale;
use App\Models\GradeScaleDetail;
use App\Http\Requests\Admin\GradeScaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeScaleController extends Controller
{
    public function index()
    {
        $gradeScales = GradeScale::with('details')->get();
        return view('admin.grade_scales.index', compact('gradeScales'));
    }

    public function create()
    {
        return view('admin.grade_scales.create');
    }

    public function store(GradeScaleRequest $request)
    {
        return DB::transaction(function () use ($request) {
            if ($request->is_default) {
                GradeScale::where('school_id', auth()->user()->school_id)->update(['is_default' => false]);
            }

            $gradeScale = GradeScale::create([
                'school_id' => auth()->user()->school_id,
                'name' => $request->name,
                'description' => $request->description,
                'is_default' => $request->is_default ?? false,
            ]);

            foreach ($request->details as $detail) {
                $gradeScale->details()->create($detail);
            }

            return redirect()->route('admin.grade-scales.index')
                ->with('success', 'Grade Scale created successfully.');
        });
    }

    public function edit(GradeScale $gradeScale)
    {
        $gradeScale->load('details');
        return view('admin.grade_scales.edit', compact('gradeScale'));
    }

    public function update(GradeScaleRequest $request, GradeScale $gradeScale)
    {
        return DB::transaction(function () use ($request, $gradeScale) {
            if ($request->is_default) {
                GradeScale::where('school_id', auth()->user()->school_id)
                    ->where('id', '!=', $gradeScale->id)
                    ->update(['is_default' => false]);
            }

            $gradeScale->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_default' => $request->is_default ?? false,
            ]);

            // Sync details: Delete existing and recreate
            $gradeScale->details()->delete();
            foreach ($request->details as $detail) {
                $gradeScale->details()->create($detail);
            }

            return redirect()->route('admin.grade-scales.index')
                ->with('success', 'Grade Scale updated successfully.');
        });
    }

    public function destroy(GradeScale $gradeScale)
    {
        $gradeScale->delete();
        return redirect()->route('admin.grade-scales.index')
            ->with('success', 'Grade Scale deleted successfully.');
    }
}
