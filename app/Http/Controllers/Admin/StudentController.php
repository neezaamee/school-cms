<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\Enrollment;
use App\Models\Campus;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentParentDetail;
use App\Models\StudentGuardianDetail;
use App\Models\StudentAcademicSystem;
use App\Models\StudentFeeConfig;
use App\Models\StudentAttachment;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Student::with(['enrollments', 'guardian']);

        if (!$user->hasRole('super admin')) {
            $query->where('school_id', $user->school_id);
            if ($user->campus_id) {
                $query->where('campus_id', $user->campus_id);
            }
        }

        $students = $query->paginate(25);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        // Ensure we only get data for the current school
        $campuses = Campus::where('school_id', $schoolId)->get();
        $gradeLevels = GradeLevel::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();

        return view('admin.students.create', compact('campuses', 'gradeLevels', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            // 1. Student Profile
            'campus_id' => 'required|exists:campuses,id',
            'admission_no' => 'required|unique:students,admission_no',
            'b_form' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date_format:d/m/Y',
            
            // 2. Parents Information
            'father_name' => 'required|string|max:255',
            'father_cnic' => 'required|string|max:15',
            'father_mobile' => 'required|string|max:15',
            'mother_name' => 'nullable|string|max:255',
            'home_address' => 'required|string|max:500',
            
            // 3. Guardian Information
            'guardian_name' => 'nullable|required_if:guardian_different,true|string|max:255',
            'guardian_cnic' => 'nullable|required_if:guardian_different,true|string|max:15',
            'guardian_mobile' => 'nullable|required_if:guardian_different,true|string|max:15',
            
            // 4. Academic Details
            'grade_level_id' => 'required|exists:grade_levels,id',
            'section_id' => 'required|exists:sections,id',
            'session_year' => 'required|string|max:20',
            
            // 5. Academic System
            'board_type' => 'required|string',
            'medium' => 'required|string',
            
            // 6. Fee Info
            'fee_plan_id' => 'nullable|exists:fee_plans,id',
        ]);

        return DB::transaction(function () use ($request, $user) {
            // 1. Create Student
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            $student = Student::create([
                'school_id' => $user->school_id,
                'campus_id' => $request->campus_id,
                'admission_no' => $request->admission_no,
                'b_form' => $request->b_form,
                'roll_no' => $request->roll_no,
                'first_name' => $request->first_name,
                'first_name_ur' => $request->first_name_ur,
                'last_name_ur' => $request->last_name_ur,
                'dob' => \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
                'gender' => $request->gender,
                'religion' => $request->religion,
                'nationality' => $request->nationality ?? 'Pakistani',
                'blood_group' => $request->blood_group,
                'is_hafiz_e_quran' => $request->boolean('is_hafiz_e_quran'),
                'is_pwd' => $request->boolean('is_pwd'),
                'medical_notes' => $request->medical_notes,
                'photo' => $photoPath,
                'status' => 'Active',
            ]);

            // 2. Parent Details
            $student->parentDetails()->create([
                'father_name' => $request->father_name,
                'father_name_ur' => $request->father_name_ur,
                'father_profession' => $request->father_profession,
                'mother_name' => $request->mother_name,
                'mother_name_ur' => $request->mother_name_ur,
                'mother_profession' => $request->mother_profession,
                'father_cnic' => $request->father_cnic,
                'mother_cnic' => $request->mother_cnic,
                'father_mobile' => $request->father_mobile,
                'mother_mobile' => $request->mother_mobile,
                'father_email' => $request->father_email,
                'monthly_income' => $request->monthly_income,
                'home_address' => $request->home_address,
                'home_address_ur' => $request->home_address_ur,
            ]);

            // 3. Guardian Details (If different)
            if ($request->filled('guardian_name')) {
                $student->guardianDetails()->create([
                    'name' => $request->guardian_name,
                    'name_ur' => $request->guardian_name_ur,
                    'relation' => $request->guardian_relation,
                    'cnic' => $request->guardian_cnic,
                    'mobile' => $request->guardian_mobile,
                    'address' => $request->guardian_address,
                    'address_ur' => $request->guardian_address_ur,
                ]);
            }

            // 4. Academic System
            $student->academicSystem()->create([
                'board_type' => $request->board_type,
                'medium' => $request->medium,
                'previous_class' => $request->previous_class,
                'previous_school' => $request->previous_school,
                'last_result_grade' => $request->last_result_grade,
                'subjects_selected' => $request->subjects_selected,
                'academic_group' => $request->academic_group,
                'shift' => $request->shift,
            ]);

            // 5. Fee Configuration
            $student->feeConfig()->create([
                'fee_plan_id' => $request->fee_plan_id,
                'scholarship_id' => $request->scholarship_id,
                'transport_fee' => $request->transport_fee ?? 0,
                'hostel_fee' => $request->hostel_fee ?? 0,
                'discount_percentage' => $request->discount_percentage ?? 0,
                'remarks' => $request->fee_remarks,
            ]);

            // 6. Enrollment
            Enrollment::create([
                'student_id' => $student->id,
                'school_id' => $user->school_id,
                'campus_id' => $request->campus_id,
                'grade_level_id' => $request->grade_level_id,
                'section_id' => $request->section_id,
                'session_year' => $request->session_year,
                'is_active' => true,
            ]);

            // 7. Handle Documents
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $type => $file) {
                    if ($file) {
                        $path = $file->store('students/attachments', 'public');
                        $student->attachments()->create([
                            'attachment_type' => $type,
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }

            return redirect()->route('admin.students.index')
                ->with('success', "Student {$student->full_name} enrolled successfully.");
        });
    }

    public function searchByBForm(Request $request)
    {
        $bForm = $request->query('bform');
        $student = Student::where('b_form', $bForm)->first();

        if ($student) {
            return response()->json([
                'found' => true,
                'student' => $student,
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function searchByParentCnic(Request $request)
    {
        $cnic = $request->query('cnic');
        $parent = StudentParentDetail::where('father_cnic', $cnic)
            ->orWhere('mother_cnic', $cnic)
            ->first();

        if ($parent) {
            return response()->json([
                'found' => true,
                'parent' => $parent,
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function getSubjectsByClass(Request $request)
    {
        $classId = $request->query('class_id');
        $subjects = Subject::where('grade_level_id', $classId)->get();

        return response()->json($subjects);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $student = Student::with(['school', 'campus', 'guardian', 'enrollments', 'attendance'])
            ->findOrFail($id);

        if (!$user->hasRole('super admin') && $student->school_id != $user->school_id) {
            abort(403);
        }

        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $student = Student::with(['school', 'campus', 'guardian', 'enrollments'])
            ->findOrFail($id);

        if (!$user->hasRole('super admin') && $student->school_id != $user->school_id) {
            abort(403);
        }

        $campuses = Campus::all();
        $gradeLevels = GradeLevel::all();
        $sections = Section::all();

        return view('admin.students.edit', compact('student', 'campuses', 'gradeLevels', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $student = Student::findOrFail($id);

        if (!$user->hasRole('super admin') && $student->school_id != $user->school_id) {
            abort(403);
        }

        $request->validate([
            'admission_no' => 'required',
            'first_name' => 'required|string|max:255',
            'dob' => 'required|date_format:d/m/Y',
            'gender' => 'required',
            'campus_id' => 'required|exists:campuses,id',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_relation' => 'required',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'section_id' => 'required|exists:sections,id',
            'session_year' => 'required',
            'photo' => 'nullable|image|max:2048',
        ]);

        return DB::transaction(function () use ($request, $student, $user) {
            // 1. Update Guardian
            if ($student->guardian) {
                $student->guardian->update([
                    'father_name' => $request->father_name,
                    'mother_name' => $request->mother_name,
                    'guardian_name' => $request->guardian_name,
                    'guardian_phone' => $request->guardian_phone,
                    'guardian_relation' => $request->guardian_relation,
                ]);
            }

            // 2. Handle Photo Upload
            $photoPath = $student->photo;
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            // 3. Update Student
            $student->update([
                'campus_id' => $request->campus_id,
                'admission_no' => $request->admission_no,
                'roll_no' => $request->roll_no,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'dob' => \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'photo' => $photoPath,
                'status' => $request->status,
            ]);

            // 4. Update Current Enrollment
            $enrollment = $student->enrollments()->where('is_active', true)->first();
            if ($enrollment) {
                $enrollment->update([
                    'grade_level_id' => $request->grade_level_id,
                    'section_id' => $request->section_id,
                    'session_year' => $request->session_year,
                ]);
            }

            return redirect()->route('admin.students.show', $student->id)
                ->with('success', "Student record for {$student->full_name} updated successfully.");
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $student = Student::findOrFail($id);

        if (!$user->hasRole('super admin') && $student->school_id != $user->school_id) {
            abort(403);
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student record deleted successfully (Soft Deleted).');
    }
}
