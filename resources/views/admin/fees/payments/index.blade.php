@extends('layouts.admin')

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Fee Collection Terminal</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.payments.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by PSID, Admission No, or Student Name..." value="{{ request('search') }}" autofocus />
                        <button type="submit" class="btn btn-primary">Find Invoice</button>
                    </div>
                    <small class="text-500 fs-11">Enter at least 3 characters to search.</small>
                </form>

                @if($invoice)
                    <div class="border rounded-3 p-3 bg-white shadow-sm border-primary">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <h5 class="mb-1 text-primary">{{ $invoice->student->full_name }}</h5>
                                <p class="mb-0 fs-11 text-700">Adm No: {{ $invoice->student->admission_no }} | Class: {{ $invoice->student->enrollments->where('is_active', true)->first()->class_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-auto text-end">
                                <div class="badge badge-subtle-{{ $invoice->status_color }}">{{ $invoice->status }}</div>
                                <div class="fs-10 text-500">PSID: {{ $invoice->psid }}</div>
                            </div>
                        </div>

                        <ul class="list-group list-group-flush fs-11 mb-3">
                            @foreach($invoice->items as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                    {{ $item->category->name }}
                                    <span class="fw-bold">Rs. {{ number_format($item->amount, 2) }}</span>
                                </li>
                            @endforeach
                            @if($invoice->fine_amount > 0)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 text-danger">
                                    Late Fee / Fine
                                    <span class="fw-bold">+ Rs. {{ number_format($invoice->fine_amount, 2) }}</span>
                                </li>
                            @endif
                        </ul>

                        <div class="bg-body-tertiary rounded p-2 mb-4">
                            <div class="d-flex justify-content-between h4 mb-0">
                                <span>Total Payable</span>
                                <span class="text-dark fw-bold">Rs. {{ number_format($invoice->balance, 2) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('admin.payments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}" />
                            <div class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label fs-11 fw-bold">Collection Amount (Rs.)</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rs.</span>
                                        <input type="number" name="amount" class="form-control" step="0.01" value="{{ $invoice->balance }}" max="{{ $invoice->balance }}" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-11 fw-bold">Receipt/Reference No.</label>
                                    <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="Optional" />
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success btn-sm w-100 py-1">Collect Cash</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="text-center py-5">
                        <span class="fas fa-search-dollar fa-4x text-200 mb-3"></span>
                        <p class="text-500">Scan a challan or search for a student to start collecting fees.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Recent Collections (Session)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-11 mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3 py-2">Student</th>
                                <th>Amount</th>
                                <th class="text-end pe-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $pay)
                            <tr>
                                <td class="ps-3 py-2">
                                    <span class="fw-bold">{{ $pay->invoice->student->full_name }}</span>
                                    <div class="fs-10 text-500">By {{ $pay->collector->name ?? 'System' }}</div>
                                </td>
                                <td class="text-success fw-bold">Rs. {{ number_format($pay->amount, 2) }}</td>
                                <td class="text-end pe-3 text-500">{{ $pay->paid_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-500">No payments recorded today.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-body-tertiary text-center py-2">
                <a href="{{ route('admin.payments.index') }}" class="fs-11 fw-bold">View Day Summary</a>
            </div>
        </div>
    </div>
</div>
@endsection
