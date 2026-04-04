<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamTerm;
use App\Models\ExamMark;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MarkEntryController extends Controller
{
    public function index(Request $request)
    {
        $examTerms = ExamTerm::latest()->get();
        $gradeLevels = GradeLevel::all();
        $sections = Section::all();

        $selectedTermId = $request->input('exam_term_id');
        $selectedGradeId = $request->input('grade_level_id');
        $selectedSectionId = $request->input('section_id');
        $selectedExamId = $request->input('exam_id');

        $exams = [];
        if ($selectedTermId && $selectedGradeId) {
            $exams = Exam::where('exam_term_id', $selectedTermId)
                ->where('grade_level_id', $selectedGradeId)
                ->get();
        }

        $students = [];
        $existingMarks = [];
        $selectedExam = null;

        if ($selectedExamId && $selectedSectionId) {
            $selectedExam = Exam::with('subject')->find($selectedExamId);
            
            // Get students enrolled in this section
            $studentIds = Enrollment::where('section_id', $selectedSectionId)
                ->where('is_active', true)
                ->pluck('student_id');

            $students = Student::whereIn('id', $studentIds)
                ->orderBy('roll_no')
                ->get();

            $existingMarks = ExamMark::where('exam_id', $selectedExamId)
                ->whereIn('student_id', $studentIds)
                ->get()
                ->keyBy('student_id');
        }

        return view('admin.mark_entry.index', compact(
            'examTerms', 'gradeLevels', 'sections', 'exams', 
            'students', 'existingMarks', 'selectedExam'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',
            'marks.*.marks_obtained' => 'nullable|numeric|min:0',
            'marks.*.is_absent' => 'boolean',
            'marks.*.remarks' => 'nullable|string|max:255',
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        return DB::transaction(function () use ($request, $exam) {
            foreach ($request->marks as $markData) {
                // Ensure marks don't exceed total marks
                if ($markData['marks_obtained'] > $exam->total_marks) {
                    throw new \Exception("Marks obtained cannot exceed total marks ({$exam->total_marks}).");
                }

                ExamMark::updateOrCreate(
                    [
                        'school_id' => Auth::user()->school_id,
                        'exam_id' => $exam->id,
                        'student_id' => $markData['student_id'],
                    ],
                    [
                        'marks_obtained' => $markData['is_absent'] ? null : $markData['marks_obtained'],
                        'is_absent' => $markData['is_absent'] ?? false,
                        'remarks' => $markData['remarks'],
                        'created_by' => Auth::id(),
                    ]
                );
            }

            return redirect()->back()->with('success', 'Marks saved successfully.');
        });
    }
}
