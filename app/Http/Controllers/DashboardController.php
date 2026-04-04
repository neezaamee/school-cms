<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\StaffProfile;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect to the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('super admin')) {
            return $this->superAdminDashboard();
        } elseif ($user->hasAnyRole(['school owner', 'principal', 'school administrator', 'school manager', 'campus manager', 'data entry operator'])) {
            return $this->schoolDashboard();
        } elseif ($user->hasRole('teacher')) {
            return view('dashboard.teacher');
        } elseif ($user->hasRole('student')) {
            return view('dashboard.student');
        }

        return view('dashboard.default');
    }

    /**
     * High-fidelity Dashboard for School Owners and Administrators.
     */
    protected function schoolDashboard()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;
        $campusId = $user->campus_id; // Null for school owners usually
        
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentMonth = $now->format('F');
        $currentYear = $now->year;

        // 1. Student Stats
        $studentQuery = Student::where('school_id', $schoolId);
        if ($campusId) $studentQuery->where('campus_id', $campusId);
        
        $stats['total_students'] = (clone $studentQuery)->count();
        $stats['active_students'] = (clone $studentQuery)->where('status', 'Active')->count();

        // 2. Staff Stats
        $staffQuery = StaffProfile::where('school_id', $schoolId);
        if ($campusId) $staffQuery->where('campus_id', $campusId);
        $stats['total_staff'] = $staffQuery->count();

        // 3. Attendance Today
        $attendanceQuery = Attendance::where('school_id', $schoolId)
            ->where('attendance_date', $today);
        if ($campusId) $attendanceQuery->where('campus_id', $campusId);
        
        $presentCount = (clone $attendanceQuery)->whereIn('status', ['Present', 'Late', 'Half Day'])->count();
        
        // Target for attendance is total active enrolled students
        $enrolledCount = Enrollment::where('school_id', $schoolId)
            ->where('is_active', true);
        if ($campusId) $enrolledCount->where('campus_id', $campusId);
        $totalEligible = $enrolledCount->count();

        $stats['attendance_percentage'] = $totalEligible > 0 ? round(($presentCount / $totalEligible) * 100) : 0;

        // 4. Financial Status (This Month)
        $invoiceQuery = Invoice::where('school_id', $schoolId)
            ->where('month', $currentMonth)
            ->where('year', $currentYear);
        if ($campusId) $invoiceQuery->where('campus_id', $campusId);

        $stats['total_invoiced'] = (clone $invoiceQuery)->sum('total_amount');
        $stats['total_collected'] = (clone $invoiceQuery)->sum('paid_amount');
        $stats['collection_percentage'] = $stats['total_invoiced'] > 0 
            ? round(($stats['total_collected'] / $stats['total_invoiced']) * 100) 
            : 0;

        // 5. Recent Activity
        $stats['recent_payments'] = Payment::with(['invoice.student', 'collector'])
            ->where('school_id', $schoolId)
            ->latest()
            ->limit(5)
            ->get();

        // 6. Setup Readiness (New)
        $readiness = app(\App\Services\SetupReadinessService::class)->getReadiness();

        return view('dashboard.school_owner', compact('stats', 'readiness'));
    }

    protected function superAdminDashboard()
    {
        // Global Stats (Mix of real and dummy for now)
        $stats['total_schools'] = \App\Models\School::count();
        $stats['total_students'] = \App\Models\Student::count();
        $stats['active_subscriptions'] = \App\Models\School::where('status', 'Active')->count();
        $stats['monthly_revenue'] = 450000; // Dummy MRR
        $stats['revenue_growth'] = 12.5; // Dummy %
        
        // Dummy Recent Schools
        $stats['recent_schools'] = collect([
            (object)['name' => 'Oakridge International', 'package' => 'Premium', 'status' => 'Active', 'joined' => '2 days ago', 'logo' => null],
            (object)['name' => 'Beaconhouse North', 'package' => 'Standard', 'status' => 'Pending', 'joined' => '5 days ago', 'logo' => null],
            (object)['name' => 'The City School East', 'package' => 'Elite', 'status' => 'Active', 'joined' => '1 week ago', 'logo' => null],
            (object)['name' => 'Roots Millennium', 'package' => 'Premium', 'status' => 'Active', 'joined' => '2 weeks ago', 'logo' => null],
            (object)['name' => 'Lahore Grammar', 'package' => 'Standard', 'status' => 'Inactive', 'joined' => '1 month ago', 'logo' => null],
        ]);

        // Dummy System Pulse (Recent Activities)
        $stats['system_pulse'] = collect([
            (object)['event' => 'New School Registered', 'user' => 'System', 'time' => '10 mins ago', 'icon' => 'fa-plus-circle', 'color' => 'text-success'],
            (object)['event' => 'Backup Completed', 'user' => 'Cron Job', 'time' => '1 hour ago', 'icon' => 'fa-database', 'color' => 'text-primary'],
            (object)['event' => 'Security Alert: Failed Login', 'user' => '192.168.1.1', 'time' => '3 hours ago', 'icon' => 'fa-exclamation-triangle', 'color' => 'text-danger'],
            (object)['event' => 'Subscription Tier Upgraded', 'user' => 'Oakridge Owner', 'time' => '5 hours ago', 'icon' => 'fa-arrow-up', 'color' => 'text-info'],
            (object)['event' => 'Patch v2.4 Applied', 'user' => 'Admin', 'time' => '1 day ago', 'icon' => 'fa-code-branch', 'color' => 'text-secondary'],
        ]);

        return view('dashboard.super_admin', compact('stats'));
    }
}
