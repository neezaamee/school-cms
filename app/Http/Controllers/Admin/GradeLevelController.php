<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Http\Requests\Admin\GradeLevelRequest;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    public function index()
    {
        $gradeLevels = GradeLevel::latest()->get();
        return view('admin.grade_levels.index', compact('gradeLevels'));
    }

    public function create()
    {
        return view('admin.grade_levels.create');
    }

    public function store(GradeLevelRequest $request)
    {
        GradeLevel::create($request->validated());

        return redirect()->route('admin.grade-levels.index')
            ->with('success', 'Grade Level created successfully.');
    }

    public function edit(GradeLevel $gradeLevel)
    {
        return view('admin.grade_levels.edit', compact('gradeLevel'));
    }

    public function update(GradeLevelRequest $request, GradeLevel $gradeLevel)
    {
        $gradeLevel->update($request->validated());

        return redirect()->route('admin.grade-levels.index')
            ->with('success', 'Grade Level updated successfully.');
    }

    public function destroy(GradeLevel $gradeLevel)
    {
        $gradeLevel->delete();

        return redirect()->route('admin.grade-levels.index')
            ->with('success', 'Grade Level deleted successfully.');
    }
}
