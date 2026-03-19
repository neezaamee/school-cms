@extends('layouts.admin')

@section('title', 'Edit Campus')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-body-tertiary">
                <h5 class="mb-0">Edit Campus: {{ $campus->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.campuses.update', $campus->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-12 text-start">
                            <label class="form-label" for="name">Campus Name</label>
                            <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $campus->name) }}" required />
                        </div>

                        @role('super admin')
                        <div class="col-md-12 text-start">
                            <label class="form-label" for="school_id">Assign to School</label>
                            <input class="form-control" type="text" value="{{ $campus->school->name }}" disabled />
                            <input type="hidden" name="school_id" value="{{ $campus->school_id }}">
                        </div>
                        @endrole

                        <div class="col-md-6 text-start">
                            <label class="form-label" for="country_id">Country</label>
                            <select class="form-select select2" id="country_id" name="country_id" required>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id', $campus->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 text-start">
                            <label class="form-label" for="city_id">City</label>
                            <select class="form-select select2" id="city_id" name="city_id" required>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $campus->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 text-start">
                            <label class="form-label" for="address">Full Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $campus->address) }}</textarea>
                        </div>

                        <div class="col-md-12 d-flex align-items-end mb-1">
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="is_main" value="0">
                                <input class="form-check-input" id="is_main" name="is_main" type="checkbox" value="1" {{ old('is_main', $campus->is_main) ? 'checked' : '' }} />
                                <label class="form-check-label mb-0" for="is_main">Set as Main Campus</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border-top pt-3">
                        <button class="btn btn-primary" type="submit">Update Campus</button>
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
        }
    });
});
</script>
@endpush
@endsection
