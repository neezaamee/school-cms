@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">Daily Attendance</h5>
                <p class="fs-11 mb-0 text-600">Filter by class and section to mark or view attendance</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.attendance.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label" for="filter_date">Date</label>
                <input class="form-control datetimepicker mask-date" id="filter_date" name="date" type="text" placeholder="dd/mm/yyyy" data-options='{"disableMobile":true,"dateFormat":"d/m/Y"}' value="{{ request('date', date('d/m/Y')) }}" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="filter_class">Class</label>
                <input class="form-control" id="filter_class" name="class" type="text" placeholder="Search Class..." value="{{ request('class') }}" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="filter_section">Section</label>
                <input class="form-control" id="filter_section" name="section" type="text" placeholder="Search Section..." value="{{ request('section') }}" />
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit">
                    <span class="fas fa-search me-1"></span>Filter Students
                </button>
            </div>
        </form>
    </div>
</div>

@if(request('class'))
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Attendance Sheet: {{ request('class') }} {{ request('section') ? ' - '.request('section') : '' }} ({{ request('date', date('d/m/Y')) }})</h6>
    </div>
    <div class="card-body p-0">
        <form action="{{ route('admin.attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="attendance_date" value="{{ request('date', date('d/m/Y')) }}" />
            
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped fs-10 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="ps-3" style="width: 100px;">Roll No</th>
                            <th>Student Name</th>
                            <th class="text-center" style="width: 300px;">Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse($students as $student)
                        <tr>
                            <td class="align-middle ps-3">{{ $student->roll_no ?? 'N/A' }}</td>
                            <td class="align-middle">
                                <strong>{{ $student->full_name }}</strong>
                                <br><small class="text-500">Adm: {{ $student->admission_no }}</small>
                            </td>
                            <td class="align-middle text-center">
                                @php 
                                    $existingAttendance = $student->attendance->where('attendance_date', request('date', date('d/m/Y')))->first();
                                    $currentStatus = $existingAttendance ? $existingAttendance->status : 'Present';
                                @endphp
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="present_{{ $student->id }}" value="Present" {{ $currentStatus == 'Present' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-success" for="present_{{ $student->id }}">P</label>

                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="late_{{ $student->id }}" value="Late" {{ $currentStatus == 'Late' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-warning" for="late_{{ $student->id }}">L</label>

                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="absent_{{ $student->id }}" value="Absent" {{ $currentStatus == 'Absent' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-danger" for="absent_{{ $student->id }}">A</label>

                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="half_{{ $student->id }}" value="Half Day" {{ $currentStatus == 'Half Day' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-info" for="half_{{ $student->id }}">H</label>
                                </div>
                            </td>
                            <td class="align-middle">
                                <input class="form-control form-control-sm" name="remarks[{{ $student->id }}]" type="text" value="{{ $existingAttendance ? $existingAttendance->remarks : '' }}" placeholder="..." />
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-500">No students found matching your filters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($students->count() > 0)
            <div class="card-footer bg-body-tertiary text-end">
                <button class="btn btn-primary" type="submit">
                    <span class="fas fa-save me-1"></span>Save Attendance
                </button>
            </div>
            @endif
        </form>
    </div>
</div>
@else
<div class="card bg-light mt-3 border-dashed">
    <div class="card-body text-center py-5">
        <div class="avatar avatar-3xl mb-3">
            <div class="avatar-name rounded-circle bg-subtle-primary"><span class="fas fa-search"></span></div>
        </div>
        <h5>Select a class and section to begin</h5>
        <p class="text-600">Enter a class name above to search for students and mark their daily attendance.</p>
    </div>
</div>
@endif
@endsection
