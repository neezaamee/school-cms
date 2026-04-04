<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamTerm;
use App\Models\GradeLevel;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $examTerms = ExamTerm::latest()->get();
        $gradeLevels = GradeLevel::all();
        $selectedTermId = $request->get('exam_term_id');

        $exams = Exam::with(['examTerm', 'gradeLevel', 'subject'])
            ->when($selectedTermId, function($q) use ($selectedTermId) {
                return $q->where('exam_term_id', $selectedTermId);
            })
            ->latest()
            ->paginate(20);

        return view('admin.exams.index', compact('exams', 'examTerms', 'gradeLevels'));
    }

    public function create()
    {
        $examTerms = ExamTerm::where('is_active', true)->get();
        if ($examTerms->isEmpty()) {
            $examTerms = ExamTerm::latest()->limit(5)->get();
        }
        $gradeLevels = GradeLevel::all();
        $subjects = Subject::all();
        return view('admin.exams.create', compact('examTerms', 'gradeLevels', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_term_id' => 'required|exists:exam_terms,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'exam_date' => 'nullable|date',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0',
            'weightage' => 'required|numeric|min:0|max:100',
        ]);

        Exam::create([
            'school_id' => auth()->user()->school_id,
            'exam_term_id' => $request->exam_term_id,
            'grade_level_id' => $request->grade_level_id,
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'exam_date' => $request->exam_date,
            'total_marks' => $request->total_marks,
            'passing_marks' => $request->passing_marks,
            'weightage' => $request->weightage,
        ]);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam scheduled successfully.');
    }

    public function edit(Exam $exam)
    {
        $examTerms = ExamTerm::all();
        $gradeLevels = GradeLevel::all();
        $subjects = Subject::where('grade_level_id', $exam->grade_level_id)->get();
        return view('admin.exams.edit', compact('exam', 'examTerms', 'gradeLevels', 'subjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'exam_term_id' => 'required|exists:exam_terms,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'exam_date' => 'nullable|date',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0',
            'weightage' => 'required|numeric|min:0|max:100',
        ]);

        $exam->update($request->all());

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }
}
