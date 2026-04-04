@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Grade Scales</h5>
                <p class="mb-0 pt-1 fs-11">Configure grading rules for examinations</p>
            </div>
            <div class="col-auto ms-auto">
                <a class="btn btn-falcon-default btn-sm" href="{{ route('admin.grade-scales.create') }}">
                    <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>New Grade Scale
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped fs-10 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="ps-3">Name</th>
                        <th>Status</th>
                        <th>Grade Rules</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradeScales as $scale)
                    <tr>
                        <td class="align-middle ps-3">
                            <h6 class="mb-0 text-nowrap">{{ $scale->name }}</h6>
                            <p class="fs-11 mb-0 text-500">{{ Str::limit($scale->description, 50) }}</p>
                        </td>
                        <td class="align-middle">
                            @if($scale->is_default)
                                <span class="badge badge-subtle-success">Default Scale</span>
                            @else
                                <span class="text-500">Secondary</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            @foreach($scale->details as $detail)
                                <span class="badge badge-outline-secondary fs-11" title="{{ $detail->min_score }}% - {{ $detail->max_score }}%">
                                    {{ $detail->name }} ({{ $detail->point }})
                                </span>
                            @endforeach
                        </td>
                        <td class="align-middle text-end pe-3">
                            <div class="dropdown font-sans-serif btn-reveal-trigger">
                                <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="dropdown-{{ $scale->id }}" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-11"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-{{ $scale->id }}">
                                    <a class="dropdown-item" href="{{ route('admin.grade-scales.edit', $scale->id) }}">Edit Scale</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.grade-scales.destroy', $scale->id) }}" method="POST" onsubmit="return confirm('Delete this scale?')">
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
                        <td colspan="4" class="text-center py-4">No grade scales configured.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
