@extends('layouts.admin')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success border-2 d-flex align-items-center" role="alert">
                <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-9"></span></div>
                <p class="mb-0 flex-1">{{ session('success') }}</p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
                <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-9"></span></div>
                <div class="flex-1">
                    <p class="mb-0 fw-bold">Please correct the following errors:</p>
                    <ul class="mb-0 fs-11">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Fee Structures (Rate Management)</h6>
                <button class="btn btn-falcon-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addStructureModal">
                    <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Add/Update Rate
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-11 mb-0 border-bottom">
                        <thead class="bg-200">
                            <tr>
                                <th class="ps-3 py-2">Class</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Due Day</th>
                                <th>Late Fine</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($structures as $st)
                            <tr>
                                <td class="ps-3 py-2 fw-bold">{{ $st->class_name }}</td>
                                <td>{{ $st->category->name }}</td>
                                <td class="text-primary fw-bold">Rs. {{ number_format($st->amount, 2) }}</td>
                                <td>Every {{ $st->due_day }}{{ in_array($st->due_day, [1, 21, 31]) ? 'st' : (in_array($st->due_day, [2, 22]) ? 'nd' : (in_array($st->due_day, [3, 23]) ? 'rd' : 'th')) }}</td>
                                <td>
                                    @if($st->fine_amount > 0)
                                        <span class="text-danger fw-bold">+ {{ $st->fine_type == 'Fixed' ? 'Rs. ' . number_format($st->fine_amount, 2) : $st->fine_amount . '%' }}</span>
                                    @else
                                        <span class="text-500 italic">No Fine</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('admin.fee-structures.destroy', $st->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-link p-0 ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Rule" onclick="return confirm('Remove this fee structure?')">
                                            <span class="text-500 fas fa-trash-alt text-danger"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-500 mb-0">No fee structures configured yet.</p>
                                    <p class="fs-10 text-400">Setup your rates here to start generating monthly invoices.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addStructureModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px">
        <div class="modal-content">
            <form action="{{ route('admin.fee-structures.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-body-tertiary">
                    <h5 class="modal-title">Configure Fee Structure</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="class_name">Class <span class="text-danger">*</span></label>
                            <select class="form-select @error('class_name') is-invalid @enderror" id="class_name" name="class_name" required>
                                <option value="">Select Class</option>
                                @foreach($gradeLevels as $gl)
                                    <option value="{{ $gl->name }}">{{ $gl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="fee_category_id">Fee Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('fee_category_id') is-invalid @enderror" id="fee_category_id" name="fee_category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="amount">Fee Amount (Rs.) <span class="text-danger">*</span></label>
                            <input class="form-control mask-amount @error('amount') is-invalid @enderror" id="amount" name="amount" type="text" value="{{ old('amount', 0) }}" required />
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <hr class="my-3 opacity-25" />
                        
                        <div class="col-md-4">
                            <label class="form-label" for="due_day">Default Due Day <span class="text-danger">*</span></label>
                            <input class="form-control" id="due_day" name="due_day" type="number" min="1" max="31" value="10" required />
                            <small class="text-500 fs-11">Day of the month</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="fine_type">Late Fine Type</label>
                            <select class="form-select" id="fine_type" name="fine_type">
                                <option value="Fixed">Fixed Amount</option>
                                <option value="Percentage">Percentage (%)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="fine_amount">Fine Amount</label>
                            <input class="form-control mask-amount @error('fine_amount') is-invalid @enderror" id="fine_amount" name="fine_amount" type="text" value="{{ old('fine_amount', 0) }}" />
                            @error('fine_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" type="submit">Save Rate Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
