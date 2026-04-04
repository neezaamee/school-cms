@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Examination Schedule</h5>
                <p class="mb-0 pt-1 fs-11">Manage and schedule individual exams for each subject</p>
            </div>
            <div class="col-auto ms-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.exams.create') }}">
                    <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Schedule Exam
                </a>
            </div>
        </div>
    </div>
    <div class="card-body bg-body-tertiary border-top">
        <form action="{{ route('admin.exams.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="exam_term_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Terms</option>
                    @foreach($examTerms as $term)
                        <option value="{{ $term->id }}" {{ request('exam_term_id') == $term->id ? 'selected' : '' }}>{{ $term->name }} ({{ $term->session_year }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.exams.index') }}" class="btn btn-falcon-default btn-sm">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-10 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Exam Name</th>
                        <th>Term</th>
                        <th>Class / Subject</th>
                        <th>Date</th>
                        <th>Marks</th>
                        <th>Weight</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr>
                        <td class="align-middle ps-3">
                            <h6 class="mb-0">{{ $exam->name }}</h6>
                        </td>
                        <td class="align-middle">{{ $exam->examTerm->name }}</td>
                        <td class="align-middle">
                            {{ $exam->gradeLevel->name }}
                            <span class="badge badge-subtle-primary ms-1">{{ $exam->subject->name }}</span>
                        </td>
                        <td class="align-middle">{{ $exam->exam_date ? $exam->exam_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="align-middle">
                            <span class="fw-bold">{{ $exam->total_marks }}</span> 
                            <small class="text-500">(Pass: {{ $exam->passing_marks }})</small>
                        </td>
                        <td class="align-middle">{{ $exam->weightage }}%</td>
                        <td class="align-middle text-end pe-3">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="dropdown-{{ $exam->id }}" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-11"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-{{ $exam->id }}">
                                    <a class="dropdown-item" href="{{ route('admin.exams.edit', $exam->id) }}">Edit Schedule</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Delete this exam schedule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No exams scheduled for the selected criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($exams->hasPages())
    <div class="card-footer border-top">
        {{ $exams->links() }}
    </div>
    @endif
</div>
@endsection
