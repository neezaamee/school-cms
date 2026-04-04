<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Term Name <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. Midterm 1" value="{{ old('name', $examTerm->name ?? '') }}" required />
    </div>
    <div class="col-md-4">
        <label class="form-label" for="session_year">Session Year <span class="text-danger">*</span></label>
        <input class="form-control @error('session_year') is-invalid @enderror" id="session_year" name="session_year" type="text" placeholder="2025-2026" value="{{ old('session_year', $examTerm->session_year ?? '') }}" required />
    </div>
    <div class="col-md-2">
        <label class="form-label" for="is_active">Status</label>
        <div class="form-check form-switch mt-1">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $examTerm->is_active ?? true) ? 'checked' : '' }} />
            <label class="form-check-label" for="is_active">Active Term</label>
        </div>
    </div>
</div>
