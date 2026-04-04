<div class="card shadow-none border mb-4 rounded-3 overflow-hidden">
    <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="icon-item icon-item-sm shadow-none bg-primary-subtle me-2">
                <span class="fas fa-rocket text-primary fs-11"></span>
            </div>
            <h6 class="mb-0">School Launchpad: Getting Started</h6>
        </div>
        @if($readiness['is_ready'])
            <button class="btn btn-link btn-sm text-600 p-0" wire:click="dismiss" title="Dismiss">
                <span class="fas fa-times"></span>
            </button>
        @endif
    </div>
    <div class="card-body p-3 p-lg-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <p class="text-700 fs-11 mb-2">Completion Progress: <span class="fw-bold text-primary">{{ $readiness['progress'] }}%</span></p>
                <div class="progress rounded-pill" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" 
                        style="width: {{ $readiness['progress'] }}%" 
                        aria-valuenow="{{ $readiness['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
            @if($readiness['can_show_quick_start'] && !$readiness['is_ready'])
            <div class="col-auto">
                <button class="btn btn-falcon-default btn-sm" wire:click="quickStart" wire:confirm="This will automatically create 5 standard classes and a basic Tuition Fee category. Continue?">
                    <span class="fas fa-magic me-1"></span>Quick Start
                </button>
            </div>
            @endif
        </div>

        <div class="row g-3">
            @foreach($readiness['stats'] as $key => $item)
            <div class="col-md-6 col-xxl-3">
                <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-between {{ $item['status'] ? 'bg-success-subtle border-success-subtle' : 'bg-body-tertiary shadow-sm' }}">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 fs-11 text-truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</h6>
                            @if($item['status'])
                                <span class="fas fa-check-circle text-success fs-10"></span>
                            @else
                                <span class="fas fa-clock text-warning fs-10"></span>
                            @endif
                        </div>
                        <p class="fs-11 text-600 mb-2">
                            @if($item['status'])
                                Successfully configured.
                            @else
                                {{ $item['required'] > 0 ? "At least {$item['required']} required." : "Configure to stay organized." }}
                            @endif
                        </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge {{ $item['status'] ? 'badge-subtle-success' : 'badge-subtle-warning' }} rounded-pill fs-11">
                            {{ $item['count'] }} Added
                        </span>
                        <a href="{{ $item['route'] }}" class="btn btn-link btn-sm p-0 fs-11 fw-bold {{ $item['status'] ? 'text-success' : 'text-primary' }}">
                            Manage <span class="fas fa-chevron-right ms-1 fs-12"></span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if(!$readiness['is_ready'])
        <div class="alert alert-warning mt-4 mb-0 border-0 p-3 shadow-none bg-warning-subtle text-warning-emphasis">
            <div class="d-flex">
                <span class="fas fa-exclamation-triangle mt-1 me-2"></span>
                <p class="mb-0 small fw-bold">Important: Please complete the required steps (Classes, Fee Categories, and Fee Structure) before you start enrolling students to ensure accurate fee generation and record management.</p>
            </div>
        </div>
        @endif
    </div>
</div>
