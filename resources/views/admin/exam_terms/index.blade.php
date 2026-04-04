@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Examination Terms</h5>
                <p class="mb-0 pt-1 fs-11">Define academic terms (e.g. 1st Term, Midterm, Final)</p>
            </div>
            <div class="col-auto ms-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.exam-terms.create') }}">
                    <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>New Term
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-10 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Term Name</th>
                        <th>Session Year</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examTerms as $term)
                    <tr>
                        <td class="align-middle ps-3">
                            <h6 class="mb-0">{{ $term->name }}</h6>
                        </td>
                        <td class="align-middle">{{ $term->session_year }}</td>
                        <td class="align-middle">
                            @if($term->is_active)
                                <span class="badge badge-subtle-success">Active Term</span>
                            @else
                                <span class="badge badge-subtle-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="align-middle text-end pe-3">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="dropdown-{{ $term->id }}" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-11"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-{{ $term->id }}">
                                    <a class="dropdown-item" href="{{ route('admin.exam-terms.edit', $term->id) }}">Edit Term</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.exam-terms.destroy', $term->id) }}" method="POST" onsubmit="return confirm('Delete this term? All related exams will be lost.')">
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
                        <td colspan="4" class="text-center py-4">No examination terms defined.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
