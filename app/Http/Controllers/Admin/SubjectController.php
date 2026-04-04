<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\GradeLevel;
use App\Http\Requests\Admin\SubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subjects = Subject::with('gradeLevels')
            ->where('school_id', $user->school_id)
            ->latest()
            ->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $user = Auth::user();
        $gradeLevels = GradeLevel::where('school_id', $user->school_id)->get();
        return view('admin.subjects.create', compact('gradeLevels'));
    }

    public function store(SubjectRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['school_id'] = $user->school_id;

        $subject = Subject::create($data);

        if ($request->has('grade_level_ids')) {
            $syncData = [];
            foreach ($request->grade_level_ids as $gl_id) {
                $syncData[$gl_id] = [
                    'is_elective' => in_array($gl_id, $request->elective_grades ?? []),
                ];
            }
            $subject->gradeLevels()->sync($syncData);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully' . ($request->grade_level_ids ? ' and linked to selected classes.' : '.'));
    }

    public function edit(Subject $subject)
    {
        $user = Auth::user();
        if ($subject->school_id !== $user->school_id) abort(403);

        $gradeLevels = GradeLevel::where('school_id', $user->school_id)->get();
        $linkedGradeLevelIds = $subject->gradeLevels->pluck('id')->toArray();
        $electiveGradeLevelIds = $subject->gradeLevels->where('pivot.is_elective', true)->pluck('id')->toArray();

        return view('admin.subjects.edit', compact('subject', 'gradeLevels', 'linkedGradeLevelIds', 'electiveGradeLevelIds'));
    }

    public function update(SubjectRequest $request, Subject $subject)
    {
        $user = Auth::user();
        if ($subject->school_id !== $user->school_id) abort(403);

        $subject->update($request->validated());

        $syncData = [];
        if ($request->has('grade_level_ids')) {
            foreach ($request->grade_level_ids as $gl_id) {
                $syncData[$gl_id] = [
                    'is_elective' => in_array($gl_id, $request->elective_grades ?? []),
                ];
            }
        }
        $subject->gradeLevels()->sync($syncData);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
