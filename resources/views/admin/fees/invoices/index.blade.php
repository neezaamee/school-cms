@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="mb-0">Fee Invoices / PSID Terminal</h6>
            </div>
            <div class="col-auto">
                <button class="btn btn-falcon-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#generateBatchModal">
                    <span class="fas fa-magic me-1"></span>Bulk Generate
                </button>
            </div>
        </div>
    </div>
    <div class="card-body bg-body-secondary border-top">
        <form action="{{ route('admin.invoices.index') }}" method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="campus_id" class="form-select form-select-sm">
                    <option value="">All Campuses</option>
                    @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['Unpaid', 'Paid', 'Partial', 'Overdue'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="psid" class="form-control form-control-sm" placeholder="Search by PSID (20-digits)" value="{{ request('psid') }}" />
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-falcon-default btn-sm w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-11 mb-0">
                <thead class="bg-200">
                    <tr>
                        <th class="ps-3 py-2">Invoice / PSID</th>
                        <th>Student</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="ps-3 py-2">
                            <div class="fw-bold text-primary">{{ $invoice->psid }}</div>
                            <small class="text-500">ID: #{{ $invoice->id }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.students.show', $invoice->student_id) }}" class="fw-bold">{{ $invoice->student->full_name }}</a>
                            <div class="text-500 fs-10">{{ $invoice->campus->name }}</div>
                        </td>
                        <td>{{ $invoice->month }} {{ $invoice->year }}</td>
                        <td class="fw-bold">Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="text-success">Rs. {{ number_format($invoice->paid_amount, 2) }}</td>
                        <td>
                            <span class="badge badge-subtle-{{ $invoice->status_color }}">{{ $invoice->status }}</span>
                        </td>
                        <td>{{ $invoice->due_date->format('d M, Y') }}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-link p-0" title="View Details">
                                <span class="fas fa-eye text-primary"></span>
                            </a>
                            <a href="{{ route('admin.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-link p-0 ms-2" title="Print Receipt/Challan">
                                <span class="fas fa-print text-dark"></span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-500 italic">No invoices found for the current filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-body-tertiary">
        {{ $invoices->links() }}
    </div>
</div>

{{-- Batch Generation Modal --}}
<div class="modal fade" id="generateBatchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.invoices.generate') }}" method="POST">
                @csrf
                <div class="modal-header bg-body-tertiary">
                    <h5 class="modal-title">Batch Invoice Generation</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Campus</label>
                            <select name="campus_id" class="form-select" required>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Class Name</label>
                            <input type="text" name="class_name" class="form-control" placeholder="e.g. Class 10" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Month</label>
                            <select name="month" class="form-select" required>
                                @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                                    <option value="{{ $m }}" {{ $m == date('F') ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" type="submit">Execute Batch Engine</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
