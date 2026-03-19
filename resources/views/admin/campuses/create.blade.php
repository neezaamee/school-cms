@extends('layouts.admin')

@section('title', 'Add Campus')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h5 class="mb-0">Add New Campus</h5>
            </div>
            <div class="card-body">
                @role('school owner')
                <div class="alert alert-info border-2 d-flex align-items-center" role="alert">
                    <div class="bg-info me-3 icon-item"><span class="fas fa-info-circle text-white fs-8"></span></div>
                    <p class="mb-0 flex-1">You can manage up to 10 campuses for your school. If you need more, please submit a feature request via the <strong>Feedback</strong> section.</p>
                </div>
                @endrole

                <form action="{{ route('admin.campuses.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-12 text-start">
                            <label class="form-label" for="name">Campus Name</label>
                            <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g., Downtown Campus" />
                        </div>

                        @role('super admin')
                        <div class="col-md-12 text-start">
                            <label class="form-label" for="school_id">Assign to School</label>
                            <select class="form-select select2" id="school_id" name="school_id" required>
                                <option value="">Select School</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">
                        @endrole

                        <div class="col-md-6 text-start">
                            <label class="form-label" for="country_id">Country</label>
                            <select class="form-select select2" id="country_id" name="country_id" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 text-start">
                            <label class="form-label" for="city_id">City</label>
                            <select class="form-select select2" id="city_id" name="city_id" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="col-md-12 text-start">
                            <label class="form-label" for="address">Full Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-12 d-flex align-items-end mb-1">
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="is_main" value="0">
                                <input class="form-check-input" id="is_main" name="is_main" type="checkbox" value="1" {{ old('is_main') ? 'checked' : '' }} />
                                <label class="form-check-label mb-0" for="is_main">Set as Main Campus</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border-top pt-3">
                        <button class="btn btn-primary" type="submit">Create Campus</button>
                        <a class="btn btn-falcon-default ms-2" href="{{ route('admin.campuses.index') }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#country_id').change(function() {
        var countryId = $(this).val();
        if (countryId) {
            $.get('/countries/' + countryId + '/cities', function(data) {
                $('#city_id').empty().append('<option value="">Select City</option>');
                $.each(data, function(index, city) {
                    $('#city_id').append('<option value="' + city.id + '">' + city.name + '</option>');
                });
            });
        } else {
            $('#city_id').empty().append('<option value="">Select City</option>');
        }
    });
});
</script>
@endpush
@endsection
