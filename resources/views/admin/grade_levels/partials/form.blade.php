<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. Grade 1" value="{{ old('name', $gradeLevel->name ?? '') }}" required />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="code">Code (Short Name)</label>
        <input class="form-control @error('code') is-invalid @enderror" id="code" name="code" type="text" placeholder="e.g. G1" value="{{ old('code', $gradeLevel->code ?? '') }}" />
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="status">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
            <option value="active" {{ (old('status', $gradeLevel->status ?? 'active') === 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $gradeLevel->status ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
