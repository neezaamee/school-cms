<?php

namespace App\Services;

use App\Models\GradeLevel;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;

class SetupReadinessService
{
    /**
     * Get the setup progress for the current user's school.
     */
    public function getReadiness()
    {
        $user = Auth::user();
        if (!$user->school_id) {
            return null;
        }

        $schoolId = $user->school_id;

        $stats = [
            'classes' => [
                'name' => 'Grade Levels / Classes',
                'count' => GradeLevel::where('school_id', $schoolId)->count(),
                'required' => 5,
                'status' => false,
                'route' => route('admin.grade-levels.index'),
            ],
            'fee_categories' => [
                'name' => 'Fee Categories',
                'count' => FeeCategory::where('school_id', $schoolId)->count(),
                'required' => 1,
                'status' => false,
                'route' => route('admin.fee-categories.index'),
            ],
            'fee_structures' => [
                'name' => 'Fee Structures',
                'count' => FeeStructure::where('school_id', $schoolId)->count(),
                'required' => 1,
                'status' => false,
                'route' => route('admin.fee-structures.index'),
            ],
            'sections' => [
                'name' => 'Sections',
                'count' => Section::where('school_id', $schoolId)->count(),
                'required' => 0, // Optional
                'status' => false,
                'route' => route('admin.sections.index'),
            ]
        ];

        // Update statuses
        $stats['classes']['status'] = $stats['classes']['count'] >= $stats['classes']['required'];
        $stats['fee_categories']['status'] = $stats['fee_categories']['count'] >= $stats['fee_categories']['required'];
        $stats['fee_structures']['status'] = $stats['fee_structures']['count'] >= $stats['fee_structures']['required'];
        $stats['sections']['status'] = $stats['sections']['count'] > 0;

        $requiredItems = ['classes', 'fee_categories', 'fee_structures'];
        $completedRequired = 0;
        foreach ($requiredItems as $item) {
            if ($stats[$item]['status']) $completedRequired++;
        }

        $isReady = $completedRequired === count($requiredItems);
        $progress = round(($completedRequired / count($requiredItems)) * 100);

        return [
            'stats' => $stats,
            'is_ready' => $isReady,
            'progress' => $progress,
            'can_show_quick_start' => $stats['classes']['count'] < 3,
        ];
    }
}
