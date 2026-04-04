@extends('layouts.admin')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Fee Categories</h6>
                <button class="btn btn-falcon-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Add New
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive scrollbar">
                    <table class="table table-sm table-striped fs-11 mb-0">
                        <thead class="bg-200">
                            <tr>
                                <th class="ps-3 py-2">Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td class="ps-3 py-2 fw-bold text-700">{{ $category->name }}</td>
                                <td>{{ $category->description ?? 'No description' }}</td>
                                <td>
                                    <span class="badge badge-subtle-{{ $category->is_active ? 'success' : 'secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-link p-0 ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <span class="text-500 fas fa-edit"></span>
                                    </button>
                                    <form action="{{ route('admin.fee-categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-link p-0 ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="return confirm('Delete this category?')">
                                            <span class="text-500 fas fa-trash-alt text-danger"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <p class="text-500">No categories defined yet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-body-tertiary">
                <h6 class="mb-0">Quick Help</h6>
            </div>
            <div class="card-body fs-11">
                <p><strong>What are Fee Categories?</strong></p>
                <p class="text-500">Categories help you group different types of charges. Common examples:</p>
                <ul class="text-500">
                    <li>Tuition Fee</li>
                    <li>Admission Fee</li>
                    <li>Exam Fee</li>
                    <li>Library Charges</li>
                </ul>
                <p class="text-500">Once created, you can link these to specific classes in <strong>Fee Structures</strong>.</p>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.fee-categories.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-body-tertiary">
                    <h5 class="modal-title">Add Fee Category</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="name">Category Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="name" name="name" type="text" placeholder="e.g. Monthly Tuition" required />
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" type="submit">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
