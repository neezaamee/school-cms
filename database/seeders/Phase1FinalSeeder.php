<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\School;
use App\Models\StaffDesignation;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Phase1FinalSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        if (!$school) return;
        
        $campus = Campus::where('school_id', $school->id)->first();
        if (!$campus) return;

        // 1. Designations (Global)
        $degs = [
            ['name' => 'Math Senior Teacher', 'category' => 'Academic'],
            ['name' => 'Campus Accountant', 'category' => 'Administrative'],
            ['name' => 'Security Lead', 'category' => 'Support'],
        ];

        foreach ($degs as $d) {
            StaffDesignation::updateOrCreate(
                ['name' => $d['name']],
                ['category' => $d['category'], 'slug' => Str::slug($d['name'])]
            );
        }

        $teacherDeg = StaffDesignation::where('name', 'Math Senior Teacher')->first();

        // 2. Staff
        StaffProfile::updateOrCreate([
            'email' => 'teacher@elaf.com'
        ], [
            'name' => 'John Doe',
            'school_id' => $school->id,
            'campus_id' => $campus->id,
            'designation_id' => $teacherDeg->id,
            'phone' => '0300-1111222',
        ]);

        // 3. Students & Enrollments
        for ($i=1; $i<=5; $i++) {
            $student = Student::updateOrCreate(['admission_no' => "ADM-00$i"], [
                'first_name' => "Student",
                'last_name' => "$i",
                'school_id' => $school->id,
                'campus_id' => $campus->id,
                'status' => 'Active',
                'gender' => 'Male',
                'dob' => '2015-01-01',
            ]);

            Enrollment::updateOrCreate(['student_id' => $student->id], [
                'school_id' => $school->id,
                'campus_id' => $campus->id,
                'class_name' => 'Grade 1',
                'section_name' => 'A',
                'session_year' => '2024-2025',
                'is_active' => true,
            ]);

            // 4. Invoices
            $invoice = Invoice::create([
                'student_id' => $student->id,
                'school_id' => $school->id,
                'campus_id' => $campus->id,
                'psid' => '11' . str_pad($school->id, 4, '0', STR_PAD_LEFT) . str_pad($student->id, 10, '0', STR_PAD_LEFT) . rand(10, 99),
                'month' => now()->format('F'),
                'year' => now()->year,
                'due_date' => now()->addDays(10),
                'subtotal' => 5000,
                'total_amount' => 5000,
                'paid_amount' => 0,
                'status' => 'Unpaid'
            ]);

            // 5. Payments (2 paid, 3 unpaid)
            if ($i <= 2) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'school_id' => $school->id,
                    'amount' => 5000,
                    'payment_method' => 'Cash',
                    'transaction_id' => 'TRX-' . Str::upper(Str::random(8)),
                    'paid_at' => now(),
                ]);

                $invoice->update([
                    'paid_amount' => 5000,
                    'status' => 'Paid'
                ]);
            }
        }
    }
}
