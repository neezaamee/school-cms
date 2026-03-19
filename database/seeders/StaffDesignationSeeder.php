<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            'Academic Staff (Teaching)' => [
                'Teacher', 'Senior Teacher', 'Subject Specialist', 'Head of Department (HOD)',
                'Lecturer', 'Assistant Teacher', 'Substitute Teacher', 'Teaching Assistant'
            ],
            'Administration / Management' => [
                'Principal', 'Vice Principal', 'Coordinator (Academic Coordinator)',
                'Campus Manager', 'School Administrator', 'Section Head'
            ],
            'Office / Clerical Staff' => [
                'Accountant', 'Clerk', 'Office Assistant', 'Receptionist', 'Data Entry Operator'
            ],
            'Support Staff' => [
                'Peon', 'Security Guard', 'Cleaner / Janitor', 'Electrician', 'Maintenance Staff'
            ],
            'Student Support Services' => [
                'Librarian', 'Lab Assistant', 'School Counselor', 'Nurse / Medical Staff', 'Sports Coach'
            ],
            'IT / Technical' => [
                'IT Administrator', 'System Administrator', 'Software Operator'
            ],
            'Transport Staff' => [
                'Driver', 'Conductor'
            ],
        ];

        foreach ($designations as $category => $titles) {
            foreach ($titles as $title) {
                \App\Models\StaffDesignation::firstOrCreate([
                    'slug' => \Illuminate\Support\Str::slug($title . '-' . $category),
                ], [
                    'name' => $title,
                    'category' => $category,
                ]);
            }
        }
    }
}
