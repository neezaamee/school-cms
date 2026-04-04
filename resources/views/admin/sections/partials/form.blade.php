<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="grade_level_id">Grade Level <span class="text-danger">*</span></label>
        <select class="form-select @error('grade_level_id') is-invalid @enderror" id="grade_level_id" name="grade_level_id" required>
            <option value="">Select Grade Level</option>
            @foreach($gradeLevels as $gradeLevel)
                <option value="{{ $gradeLevel->id }}" {{ (old('grade_level_id', $section->grade_level_id ?? '') == $gradeLevel->id) ? 'selected' : '' }}>
                    {{ $gradeLevel->name }}
                </option>
            @endforeach
        </select>
        @error('grade_level_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="name">Section Name <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. A, Blue, Lotus" value="{{ old('name', $section->name ?? '') }}" required />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="capacity">Capacity</label>
        <input class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" type="number" value="{{ old('capacity', $section->capacity ?? 30) }}" />
        @error('capacity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
            <option value="active" {{ (old('status', $section->status ?? 'active') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $section->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
