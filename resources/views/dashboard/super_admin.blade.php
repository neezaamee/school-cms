@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="row g-3 mb-3">
    <!-- Row 1: KPI Stats -->
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-primary fas fa-university me-2">Total Schools</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">{{ $stats['total_schools'] }}</p>
                        <p class="fs-11 text-600 mb-0"><span class="fas fa-caret-up text-success"></span> 5.8% since last month</p>
                    </div>
                    <div class="col-auto">
                        <div class="badge badge-subtle-primary rounded-pill">Active: {{ $stats['active_subscriptions'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-info fas fa-user-graduate me-2">Global Students</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">{{ number_format($stats['total_students']) }}</p>
                        <p class="fs-11 text-600 mb-0">Across all registered schools</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-success fas fa-coins me-2">Estimated MRR</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">Rs. {{ number_format($stats['monthly_revenue']) }}</p>
                        <p class="fs-11 text-600 mb-0"><span class="fas fa-arrow-up text-success"></span> {{ $stats['revenue_growth'] }}% growth</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-3">
        <div class="card h-md-100">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 text-warning fas fa-bolt me-2">System Pulse</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <div class="col">
                        <p class="font-sans-serif lh-1 mb-1 fs-5 fw-bold">Healthy</p>
                        <p class="fs-11 text-600 mb-0">Uptime: 99.98% (Last 30d)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <!-- Row 2: Charts -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary d-flex flex-between-center py-2">
                <h6 class="mb-0">Subscription Growth (ECharts)</h6>
                <div class="dropdown font-sans-serif btn-reveal-trigger">
                    <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="dropdown-subscription-chart" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs-11"></span></button>
                    <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-subscription-chart">
                        <a class="dropdown-item" href="#!">View Report</a>
                        <a class="dropdown-item" href="#!">Export data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- ECharts Placeholder -->
                <div class="echart-line-chart-example" style="min-height: 300px;" data-echart-responsive="true"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary py-2">
                <h6 class="mb-0">Package Distribution (Chart.js)</h6>
            </div>
            <div class="card-body d-flex flex-center">
                <!-- Chart.js Placeholder -->
                <canvas id="packageDistributionChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <!-- Row 3: Tables & Activity -->
    <div class="col-xxl-8">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0">Recent School Registrations</h6>
                    </div>
                    <div class="col-auto">
                        <a class="btn btn-link btn-sm px-0" href="{{ route('schools.index') }}">View all registrations<span class="fas fa-chevron-right ms-1 fs-11"></span></a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-11 mb-0">
                        <thead class="bg-200">
                            <tr>
                                <th class="ps-3 py-2">School Name</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_schools'] as $school)
                            <tr>
                                <td class="ps-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xl me-2">
                                            <div class="avatar-name rounded-circle"><span>{{ substr($school->name, 0, 1) }}</span></div>
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 fs-11 text-900">{{ $school->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $school->package }}</td>
                                <td>
                                    @php
                                        $color = match($school->status) {
                                            'Active' => 'success',
                                            'Pending' => 'warning',
                                            'Inactive' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge badge-subtle-{{ $color }} rounded-pill">{{ $school->status }}</span>
                                </td>
                                <td class="text-end pe-3 text-500">{{ $school->joined }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary py-2">
                <h6 class="mb-0">System Activity Pulse</h6>
            </div>
            <div class="card-body">
                <div class="scrollbar-overlay" style="max-height: 400px;">
                    @foreach($stats['system_pulse'] as $pulse)
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-item icon-item-sm shadow-none bg-primary-subtle {{ $pulse->color }} me-3">
                            <span class="fas {{ $pulse->icon }} fs-11"></span>
                        </div>
                        <div class="flex-1">
                            <h6 class="mb-0 fs-11">{{ $pulse->event }}</h6>
                            <p class="fs-11 text-500 mb-0">{{ $pulse->user }} - {{ $pulse->time }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <hr class="my-3" />
                <div class="row g-3">
                    <div class="col-6">
                        <a class="btn btn-app btn-falcon-primary w-100" href="{{ route('schools.create') }}">
                            <span class="fas fa-university d-block fs-3 mb-2"></span>Add School
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-app btn-falcon-info w-100" href="{{ route('subscription-packages.index') }}">
                            <span class="fas fa-tags d-block fs-3 mb-2"></span>Packages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('vendors/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('vendors/chart/chart.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- ECharts: Subscription Growth ---
        const lineChartEl = document.querySelector('.echart-line-chart-example');
        if (lineChartEl) {
            const userOptions = JSON.parse(lineChartEl.getAttribute('data-options') || '{}');
            const lineChart = window.echarts.init(lineChartEl);
            const options = {
                tooltip: { trigger: 'axis', axisPointer: { type: 'none' } },
                xAxis: {
                    type: 'category',
                    data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    axisLine: { show: false },
                    axisTick: { show: false },
                    axisLabel: { color: '#9da9bb', margin: 15 }
                },
                yAxis: {
                    type: 'value',
                    splitLine: { lineStyle: { color: '#edf2f9' } },
                    axisLabel: { color: '#9da9bb' }
                },
                series: [{
                    data: [10, 15, 12, 25, 30, 45, 50],
                    type: 'line',
                    smooth: true,
                    lineStyle: { width: 3, color: '#2c7be5' },
                    itemStyle: { color: '#2c7be5' },
                    areaStyle: {
                        color: {
                            type: 'linear', x: 0, y: 0, x2: 0, y2: 1,
                            colorStops: [{ offset: 0, color: 'rgba(44, 123, 229, 0.2)' }, { offset: 1, color: 'rgba(44, 123, 229, 0)' }]
                        }
                    }
                }],
                grid: { right: '10px', left: '30px', bottom: '10%', top: '10px' }
            };
            lineChart.setOption(window.lodash.merge(options, userOptions));
            window.addEventListener('resize', () => lineChart.resize());
        }

        // --- Chart.js: Package Distribution ---
        const pieCtx = document.getElementById('packageDistributionChart');
        if (pieCtx) {
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Premium', 'Standard', 'Elite', 'Free'],
                    datasets: [{
                        data: [40, 30, 10, 20],
                        backgroundColor: ['#2c7be5', '#00d27a', '#f5803e', '#e3e6ed'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
