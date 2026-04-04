@extends('layouts.admin')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Staff Designations / Positions</h6>
                <button class="btn btn-falcon-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
                    <span class="fas fa-plus me-1"></span>Add New Position
                </button>
            </div>
            <div class="card-body">
                @forelse($designations as $category => $list)
                    <h6 class="text-uppercase text-500 mb-2 border-bottom pb-1">{{ $category }}</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm fs-11">
                            <tbody>
                                @foreach($list as $st)
                                    <tr>
                                        <td class="fw-bold">{{ $st->name }}</td>
                                        <td class="text-500">{{ $st->profiles_count ?? $st->profiles()->count() }} Staff Members</td>
                                        <td class="text-end">
                                            <form action="{{ route('admin.staff-designations.destroy', $st->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-link p-0 ms-2" type="submit" onclick="return confirm('Remove this position?')">
                                                    <span class="fas fa-trash-alt text-danger"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <p class="text-500 italic">No designations defined for this school.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Setup Guide</h6>
            </div>
            <div class="card-body fs-11">
                <p><strong>Common Categories:</strong></p>
                <ul class="text-500">
                    <li><strong>Academic</strong>: Senior Teacher, Lab Instructor, Librarian.</li>
                    <li><strong>Admin</strong>: Accountant, Receptionist, Manager.</li>
                    <li><strong>Service</strong>: Security Guard, Cleaner, Driver.</li>
                </ul>
                <p class="text-500">Designations help you group staff for payroll and reporting in the future.</p>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addDesignationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.staff-designations.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-body-tertiary">
                    <h5 class="modal-title">New Staff Position</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Position Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Senior Accountant" required />
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="Academic">Academic (Teachers/Librarians)</option>
                            <option value="Administrative">Administrative (Accounts/Office)</option>
                            <option value="Support">Support Staff (Security/Transport)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" type="submit">Add Position</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
