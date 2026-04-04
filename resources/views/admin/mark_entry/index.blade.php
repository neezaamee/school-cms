@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0">Student Mark Entry</h5>
        <p class="mb-0 fs-11">Enter or update student marks for scheduled examinations</p>
    </div>
    <div class="card-body border-top">
        <form action="{{ route('admin.mark-entry.index') }}" method="GET" id="filter-form">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label" for="exam_term_id">Exam Term</label>
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
                    <label class="form-label" for="exam_id">Select Exam</label>
                    <select class="form-select form-select-sm" id="exam_id" name="exam_id" required>
                        <option value="">Select Exam</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }} ({{ $exam->subject->name }})</option>
                        @endforeach
                    </select>
                    @if(request('exam_term_id') && request('grade_level_id') && count($exams) == 0)
                        <small class="text-danger">No exams scheduled for this selection.</small>
                    @endif
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
                <div class="col-12 text-end">
                    <a href="{{ route('admin.mark-entry.index') }}" class="btn btn-link btn-sm me-2">Clear</a>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Find Students</button>
                </div>
            </div>
        </form>
    </div>

    @if(count($students) > 0 && $selectedExam)
    <div class="card-body p-0 border-top">
        <div class="bg-body-secondary px-3 py-2 d-flex justify-content-between align-items-center">
            <div>
                <span class="badge badge-subtle-primary me-2">{{ $selectedExam->examTerm->name }}</span>
                <span class="fw-bold">{{ $selectedExam->name }}</span> - {{ $selectedExam->subject->name }}
            </div>
            <div>
                <span class="fs-11 text-500">Total Marks:</span> <span class="badge badge-subtle-info">{{ $selectedExam->total_marks }}</span>
            </div>
        </div>
        <form action="{{ route('admin.mark-entry.store') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped fs-10 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="ps-3" style="width: 100px;">Roll No</th>
                            <th>Student Name</th>
                            <th style="width: 150px;">Marks Obtained</th>
                            <th class="text-center" style="width: 100px;">Absent</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php $mark = $existingMarks[$student->id] ?? null; @endphp
                        <tr>
                            <td class="align-middle ps-3">{{ $student->roll_no ?? 'N/A' }}</td>
                            <td class="align-middle">
                                <h6 class="mb-0 truncate">{{ $student->full_name }}</h6>
                                <input type="hidden" name="marks[{{ $index }}][student_id]" value="{{ $student->id }}">
                            </td>
                            <td class="align-middle">
                                <input type="number" step="0.01" max="{{ $selectedExam->total_marks }}" name="marks[{{ $index }}][marks_obtained]" 
                                    class="form-control form-control-sm mark-input" 
                                    value="{{ old("marks.{$index}.marks_obtained", $mark ? $mark->marks_obtained : '') }}"
                                    {{ ($mark && $mark->is_absent) ? 'disabled' : '' }}>
                            </td>
                            <td class="align-middle text-center">
                                <div class="form-check form-check-inline me-0">
                                    <input class="form-check-input absent-check" type="checkbox" name="marks[{{ $index }}][is_absent]" value="1" 
                                        {{ old("marks.{$index}.is_absent", $mark && $mark->is_absent) ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="align-middle pe-3">
                                <input type="text" name="marks[{{ $index }}][remarks]" class="form-control form-control-sm" 
                                    placeholder="Remarks..." value="{{ old("marks.{$index}.remarks", $mark ? $mark->remarks : '') }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-body-tertiary text-end">
                <button type="submit" class="btn btn-primary px-5">Save All Marks</button>
            </div>
        </form>
    </div>
    @elseif(request('exam_id') && request('section_id'))
    <div class="card-body border-top text-center py-5">
        <div class="avatar avatar-4xl mb-3">
            <span class="fas fa-users-slash text-200"></span>
        </div>
        <h5 class="text-500">No students found in this section.</h5>
        <p class="fs-11 text-500">Ensure students are enrolled in this section for the current session.</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const gradeSelect = $('#grade_level_id');
        const sectionSelect = $('#section_id');
        const termSelect = $('#exam_term_id');
        const examSelect = $('#exam_id');
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

        // Logic to toggle inputs on absent check
        $('.absent-check').on('change', function() {
            const row = $(this).closest('tr');
            const markInput = row.find('.mark-input');
            if ($(this).is(':checked')) {
                markInput.val('').attr('disabled', true);
            } else {
                markInput.attr('disabled', false);
            }
        });

        gradeSelect.on('change', filterSections);
        
        // Auto-submit filter form only when exam_id or section_id is changed? 
        // Better to let them click the button for multi-tenant stability.
    });
</script>
@endpush
@endsection
