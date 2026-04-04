<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\GradeLevel;
use App\Models\Campus;
use App\Http\Requests\Admin\SectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sections = Section::with(['gradeLevel', 'campus'])
            ->where('school_id', $user->school_id)
            ->latest()
            ->get();
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $user = Auth::user();
        $gradeLevels = GradeLevel::where('school_id', $user->school_id)->get();
        return view('admin.sections.create', compact('gradeLevels'));
    }

    public function store(SectionRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['school_id'] = $user->school_id;
        
        Section::create($data);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $user = Auth::user();
        if ($section->school_id !== $user->school_id) abort(403);
        
        $gradeLevels = GradeLevel::where('school_id', $user->school_id)->get();
        return view('admin.sections.edit', compact('section', 'gradeLevels'));
    }

    public function update(SectionRequest $request, Section $section)
    {
        $user = Auth::user();
        if ($section->school_id !== $user->school_id) abort(403);

        $section->update($request->validated());

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $user = Auth::user();
        if ($section->school_id !== $user->school_id) abort(403);

        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}
