<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;
use App\Models\GradeLevel;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class NormalizeAcademicData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:normalize-academics';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Normalize legacy string-based enrollment data into relational IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting normalization of academic data...');

        $enrollments = Enrollment::whereNull('grade_level_id')
            ->whereNotNull('class_name')
            ->get();

        if ($enrollments->isEmpty()) {
            $this->info('No legacy enrollment records found for normalization.');
            return;
        }

        $bar = $this->output->createProgressBar(count($enrollments));
        $bar->start();

        foreach ($enrollments as $enrollment) {
            DB::transaction(function () use ($enrollment) {
                // 1. Handle Grade Level
                $gradeLevel = GradeLevel::firstOrCreate(
                    [
                        'school_id' => $enrollment->school_id,
                        'name' => $enrollment->class_name,
                    ],
                    [
                        'code' => strtoupper(substr($enrollment->class_name, 0, 5)),
                    ]
                );

                // 2. Handle Section
                $sectionName = $enrollment->section_name ?? 'Default';
                $section = Section::firstOrCreate(
                    [
                        'school_id' => $enrollment->school_id,
                        'grade_level_id' => $gradeLevel->id,
                        'name' => $sectionName,
                        'campus_id' => $enrollment->campus_id,
                    ]
                );

                // 3. Update Enrollment
                $enrollment->update([
                    'grade_level_id' => $gradeLevel->id,
                    'section_id' => $section->id,
                ]);
            });

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Normalization completed successfully.');
    }
}
