<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $rawDate = $request->get('date', date('d/m/Y'));
        try {
            $date = \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $date = date('Y-m-d');
        }
        $className = $request->get('class');
        $sectionName = $request->get('section');

        $students = collect();

        if ($className) {
            $query = \App\Models\Student::with(['enrollments', 'attendance' => function ($q) use ($date) {
                $q->where('attendance_date', $date);
            }]);

            if (!$user->hasRole('super admin')) {
                $query->where('school_id', $user->school_id);
                if ($user->campus_id) {
                    $query->where('campus_id', $user->campus_id);
                }
            }

            // Filter by enrollment
            $query->whereHas('enrollments', function ($q) use ($className, $sectionName) {
                $q->where('is_active', true)
                  ->where('class_name', $className);
                if ($sectionName) {
                    $q->where('section_name', $sectionName);
                }
            });

            $students = $query->get();
        }

        return view('admin.attendance.index', compact('students', 'rawDate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $rawDate = $request->input('attendance_date');
        try {
            $date = \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $date = date('Y-m-d');
        }
        $statuses = $request->input('status', []);
        $remarks = $request->input('remarks', []);

        return DB::transaction(function () use ($user, $date, $statuses, $remarks) {
            foreach ($statuses as $studentId => $status) {
                $student = Student::findOrFail($studentId);
                
                // Security Check: Ensure student belongs to school
                if (!$user->hasRole('super admin') && $student->school_id != $user->school_id) {
                    continue;
                }

                $enrollment = $student->enrollments()->where('is_active', true)->first();

                \App\Models\Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId, 
                        'attendance_date' => $date
                    ],
                    [
                        'enrollment_id' => $enrollment ? $enrollment->id : null,
                        'school_id' => $student->school_id,
                        'campus_id' => $student->campus_id,
                        'status' => $status,
                        'remarks' => $remarks[$studentId] ?? null,
                    ]
                );
            }
        });

        return back()->with('success', 'Attendance updated successfully for ' . $date);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
