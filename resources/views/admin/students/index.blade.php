@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Student Directory</h5>
                <p class="mb-0 pt-1 fs-11">Manage all students enrolled in your school/campus</p>
            </div>
            <div class="col-auto ms-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.students.create') }}">
                    <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>New Admission
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="falcon-data-table">
            <table class="table table-sm table-striped fs-10 mb-0 data-table" data-datatables='{"paging":true,"searching":true,"responsive":true,"pageLength":10,"order":[[0,"asc"]],"info":true,"lengthChange":true,"dom":"<\"row mx-1\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6\"f>><\"table-responsive scrollbar\"tr><\"row g-0 align-items-center justify-content-center justify-content-sm-between\"<\"col-auto mb-2 mb-sm-0 px-3\"i><\"col-auto px-3\"p>>","language":{"paginate":{"next":"<span class=\"fas fa-chevron-right\"></span>","previous":"<span class=\"fas fa-chevron-left\"></span>"}}}'>
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort">Adm. No</th>
                        <th class="sort">Student Name</th>
                        <th class="sort">Class/Section</th>
                        <th class="sort">Parent/Guardian</th>
                        <th class="sort">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($students as $student)
                    <tr>
                        <td class="align-middle white-space-nowrap">{{ $student->admission_no }}</td>
                        <td class="align-middle white-space-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl me-2">
                                    <img class="rounded-circle" src="{{ $student->photo ? asset('storage/' . $student->photo) : asset('assets/img/team/avatar.png') }}" alt="" />
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-0 ps-2 truncate">{{ $student->full_name }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle white-space-nowrap">
                            @php $latestEnrollment = $student->enrollments->where('is_active', true)->first(); @endphp
                            @if($latestEnrollment)
                                {{ $latestEnrollment->gradeLevel->name ?? 'N/A' }} 
                                <span class="badge badge-subtle-primary ms-1">{{ $latestEnrollment->section->name ?? 'N/A' }}</span>
                            @else
                                <span class="text-warning">Not Enrolled</span>
                            @endif
                        </td>
                        <td class="align-middle white-space-nowrap">
                            {{ $student->guardian ? $student->guardian->guardian_name : 'N/A' }}
                        </td>
                        <td class="align-middle white-space-nowrap">
                            <span class="badge rounded-pill @if($student->status == 'Active') badge-subtle-success @else badge-subtle-secondary @endif">
                                {{ $student->status }}
                            </span>
                        </td>
                        <td class="align-middle white-space-nowrap text-end">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="student-dropdown-{{ $student->id }}" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-11"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="student-dropdown-{{ $student->id }}">
                                    <a class="dropdown-item" href="{{ route('admin.students.show', $student->id) }}">View Profile</a>
                                    <a class="dropdown-item" href="{{ route('admin.students.edit', $student->id) }}">Edit Record</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
