<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\GradeLevel;
use App\Models\FeeCategory;
use App\Services\SetupReadinessService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OnboardingWizard extends Component
{
    public $readiness;
    public $isDismissed = false;

    public function mount(SetupReadinessService $service)
    {
        $this->readiness = $service->getReadiness();
        
        // If 100% complete and they've seen it, we might want to hide it.
        // For now, we show it if they are not 100% ready.
    }

    public function quickStart()
    {
        $user = Auth::user();
        if (!$user->school_id) return;

        $schoolId = $user->school_id;

        DB::transaction(function () use ($schoolId) {
            // 1. Create 5 standard classes if they don't exist
            $standardClasses = [
                ['name' => 'Nursery', 'code' => 'NUR'],
                ['name' => 'Prep', 'code' => 'PREP'],
                ['name' => 'Class 1', 'code' => 'CL1'],
                ['name' => 'Class 2', 'code' => 'CL2'],
                ['name' => 'Class 3', 'code' => 'CL3'],
            ];

            foreach ($standardClasses as $class) {
                GradeLevel::firstOrCreate(
                    ['school_id' => $schoolId, 'name' => $class['name']],
                    ['code' => $class['code'], 'status' => 'Active']
                );
            }

            // 2. Create a default Fee Category if none exists
            FeeCategory::firstOrCreate(
                ['school_id' => $schoolId, 'name' => 'Monthly Tuition'],
                ['description' => 'Standard monthly tuition fees', 'is_active' => true]
            );
        });

        session()->flash('success', 'Quick Start successful! 5 basic classes and a fee category have been created for you.');
        
        // Refresh readiness
        $this->readiness = app(SetupReadinessService::class)->getReadiness();
        
        $this->dispatch('refreshDashboard'); // Optional: notify parent if needed
    }

    public function dismiss()
    {
        $this->isDismissed = true;
    }

    public function render()
    {
        if ($this->isDismissed) {
            return <<<'HTML'
                <div></div>
            HTML;
        }

        return view('livewire.admin.dashboard.onboarding-wizard');
    }
}
