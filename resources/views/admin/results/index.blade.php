@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Academic Performance Reports</h5>
        <p class="mb-0 fs-11">Analyze term-wise results and student performance metrics</p>
    </div>
    <div class="card-body border-top">
        <form action="{{ route('admin.results.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="exam_term_id">Examination Term</label>
                    <select class="form-select form-select-sm" id="exam_term_id" name="exam_term_id" required>
                        <option value="">Select Term</option>
                        @foreach($examTerms as $term)
                            <option value="{{ $term->id }}" {{ request('exam_term_id') == $term->id ? 'selected' : '' }}>{{ $term->name }} ({{ $term->session_year }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="grade_level_id">Grade Level</label>
                    <select class="form-select form-select-sm" id="grade_level_id" name="grade_level_id" required>
                        <option value="">Select Grade</option>
                        @foreach($gradeLevels as $grade)
                            <option value="{{ $grade->id }}" {{ request('grade_level_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="section_id">Section</label>
                    <select class="form-select form-select-sm" id="section_id" name="section_id" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-grade="{{ $section->grade_level_id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    @if(count($students) > 0)
    <div class="card-body p-0 border-top">
        <div class="bg-body-secondary px-3 py-2 d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">Performance Summary</span> 
                <span class="fs-11 text-500 ms-2">Total Exams in Term: {{ $examsCount }}</span>
            </div>
            <div class="dropdown font-sans-serif btn-reveal-trigger">
                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" data-bs-toggle="dropdown">
                    <span class="fas fa-print fs-11"></span> Export Results
                </button>
                <div class="dropdown-menu dropdown-menu-end border py-2">
                    <a class="dropdown-item" href="#!">Section Broad Sheet (Excel)</a>
                    <a class="dropdown-item" href="#!">Result Cards (PDF)</a>
                </div>
            </div>
        </div>
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-10 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Roll No</th>
                        <th>Student Name</th>
                        <th class="text-center">Total Marks</th>
                        <th class="text-center">Percentage</th>
                        <th class="text-center">Grade</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    @php $res = $results[$student->id]; @endphp
                    <tr>
                        <td class="align-middle ps-3">{{ $student->roll_no ?? 'N/A' }}</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-l me-2">
                                    <div class="avatar-name rounded-circle"><span>{{ substr($student->full_name, 0, 1) }}</span></div>
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-0 truncate">{{ $student->full_name }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center fw-bold text-700">
                            {{ $res['total_obtained'] }} / {{ $res['total_possible'] }}
                        </td>
                        <td class="align-middle text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="progress me-2 rounded-pill" style="height: 5px; width: 60px;">
                                    <div class="progress-bar rounded-pill {{ $res['percentage'] < 33 ? 'bg-danger' : ($res['percentage'] < 60 ? 'bg-warning' : 'bg-success') }}" 
                                        role="progressbar" style="width: {{ $res['percentage'] }}%" aria-valuenow="{{ $res['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="fw-semi-bold">{{ $res['percentage'] }}%</span>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            <span class="badge badge-subtle-{{ 
                                $res['grade'] == 'F' ? 'danger' : 
                                (in_array($res['grade'], ['A+', 'A', 'B']) ? 'success' : 'warning') 
                            }} fs-11">{{ $res['grade'] }}</span>
                        </td>
                        <td class="align-middle text-end pe-3">
                            <a class="btn btn-link btn-sm text-600 px-0" href="#!" data-bs-toggle="tooltip" title="View Result Card">
                                <span class="fas fa-eye fs-11"></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(request('exam_term_id') && request('section_id'))
    <div class="card-body border-top text-center py-5">
        <div class="avatar avatar-4xl mb-3">
            <span class="fas fa-chart-line text-200"></span>
        </div>
        <h5 class="text-500">No results calculated for this term yet.</h5>
        <p class="fs-11 text-500">Ensure marks have been entered for at least one exam in this term.</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const gradeSelect = $('#grade_level_id');
        const sectionSelect = $('#section_id');
        const allSections = sectionSelect.find('option').clone();

        function filterSections() {
            const gradeId = gradeSelect.val();
            const currentSecId = sectionSelect.val();
            sectionSelect.find('option:not(:first)').remove();
            allSections.each(function() {
                const opt = $(this);
                if (opt.val() === '') return;
                if (!gradeId || opt.data('grade') == gradeId) {
                    sectionSelect.append(opt);
                }
            });
            sectionSelect.val(currentSecId);
        }

        gradeSelect.on('change', filterSections);
        if(gradeSelect.val()) filterSections();
    });
</script>
@endpush
@endsection
