@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header bg-body-tertiary">
        <h6 class="mb-0">Revenue Reporting Hub</h6>
    </div>
    <div class="card-body bg-body-secondary border-top">
        <form action="{{ route('admin.finance.reports.collection') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fs-11 fw-bold">Start Date</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate->toDateString() }}" />
            </div>
            <div class="col-md-3">
                <label class="form-label fs-11 fw-bold">End Date</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate->toDateString() }}" />
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filter</button>
            </div>
            <div class="col-md-4 text-end">
                <div class="fs-10 text-500">Period Collection</div>
                <div class="h4 mb-0 text-success fw-bold">Rs. {{ number_format($totalCollected, 2) }}</div>
            </div>
        </form>
    </div>
</div>

<div class="card h-100">
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-11 mb-0">
                <thead class="bg-200">
                    <tr>
                        <th class="ps-3 py-2">Transaction ID</th>
                        <th>Student / PSID</th>
                        <th>Method</th>
                        <th>Collected By</th>
                        <th>Amount</th>
                        <th class="text-end pe-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                    <tr>
                        <td class="ps-3 py-2">
                            <code class="text-primary">#{{ $pay->transaction_id ?? $pay->id }}</code>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $pay->invoice->student->full_name }}</div>
                            <div class="text-500 fs-10">PSID: {{ $pay->invoice->psid }}</div>
                        </td>
                        <td><span class="badge badge-subtle-info">{{ $pay->payment_method }}</span></td>
                        <td>{{ $pay->collector->name ?? 'System' }}</td>
                        <td class="fw-bold text-success">Rs. {{ number_format($pay->amount, 2) }}</td>
                        <td class="text-end pe-3">{{ $pay->paid_at->format('d M, Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <p class="text-500 mb-0">No collections found for this period.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-body-tertiary">
        {{ $payments->links() }}
    </div>
</div>
@endsection
