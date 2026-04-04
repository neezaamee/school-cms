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
use App\Models\GradeScale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $examTerms = ExamTerm::latest()->get();
        $gradeLevels = GradeLevel::all();
        $sections = Section::all();

        $selectedTermId = $request->input('exam_term_id');
        $selectedGradeId = $request->input('grade_level_id');
        $selectedSectionId = $request->input('section_id');

        $students = [];
        $results = [];
        $examsCount = 0;

        if ($selectedTermId && $selectedSectionId) {
            // Get all exams for this term and grade level
            $termExams = Exam::where('exam_term_id', $selectedTermId)
                ->where('grade_level_id', $selectedGradeId)
                ->get();
            
            $examsCount = $termExams->count();

            // Get students in section
            $studentIds = Enrollment::where('section_id', $selectedSectionId)
                ->where('is_active', true)
                ->pluck('student_id');

            $students = Student::whereIn('id', $studentIds)
                ->orderBy('roll_no')
                ->get();

            // Fetch marks for these students and exams
            $allMarks = ExamMark::whereIn('exam_id', $termExams->pluck('id'))
                ->whereIn('student_id', $studentIds)
                ->get()
                ->groupBy('student_id');

            // Find default grade scale
            $gradeScale = GradeScale::where('school_id', Auth::user()->school_id)
                ->where('is_default', true)
                ->first() ?? GradeScale::where('school_id', Auth::user()->school_id)->first();

            foreach ($students as $student) {
                $studentMarks = $allMarks->get($student->id, collect());
                $totalObtained = 0;
                $totalPossible = 0;
                $weightedPercentageSum = 0;
                $weightageTotal = 0;

                foreach ($termExams as $exam) {
                    $mark = $studentMarks->firstWhere('exam_id', $exam->id);
                    if ($mark && !$mark->is_absent) {
                        $obtained = $mark->marks_obtained;
                        $totalObtained += $obtained;
                        
                        // Calculate contribution based on weightage
                        $examPercentage = ($obtained / $exam->total_marks) * 100;
                        $weightedContribution = ($examPercentage * $exam->weightage) / 100;
                        $weightedPercentageSum += $weightedContribution;
                        $weightageTotal += $exam->weightage;
                    }
                    $totalPossible += $exam->total_marks;
                }

                $finalPercentage = $weightageTotal > 0 ? ($weightedPercentageSum / $weightageTotal) * 100 : 0;
                
                // Final Grade calculation
                $grade = 'N/A';
                if ($gradeScale) {
                    $gradeDetail = $gradeScale->details()
                        ->where('min_score', '<=', $finalPercentage)
                        ->where('max_score', '>=', $finalPercentage)
                        ->first();
                    $grade = $gradeDetail ? $gradeDetail->name : 'F';
                }

                $results[$student->id] = [
                    'total_obtained' => $totalObtained,
                    'total_possible' => $totalPossible,
                    'percentage' => round($finalPercentage, 2),
                    'grade' => $grade,
                ];
            }
        }

        return view('admin.results.index', compact(
            'examTerms', 'gradeLevels', 'sections', 
            'students', 'results', 'examsCount'
        ));
    }
}
