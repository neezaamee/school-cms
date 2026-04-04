<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Subject Name <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. Mathematics" value="{{ old('name', $subject->name ?? '') }}" required />
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="code">Subject Code</label>
        <input class="form-control @error('code') is-invalid @enderror" id="code" name="code" type="text" placeholder="e.g. MATH-101" value="{{ old('code', $subject->code ?? '') }}" />
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" placeholder="Brief overview of subject coverage...">{{ old('description', $subject->description ?? '') }}</textarea>
    </div>

    <div class="col-12 mt-4">
        <div class="bg-body-tertiary p-3 border rounded">
            <h6 class="mb-2">Hybrid Mapping: Assign to Classes (Optional)</h6>
            <p class="text-500 fs-11 mb-3">You can add this subject to the pool first and link classes later, or assign it to multiple classes now with different statuses.</p>
            
            <div class="table-responsive">
                <table class="table table-sm table-bordered bg-white fs-10 mb-0">
                    <thead class="bg-200">
                        <tr>
                            <th class="ps-2">Select Class</th>
                            <th class="text-center">Assign?</th>
                            <th class="text-center">Is Elective?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeLevels as $gl)
                            @php
                                $isLinked = in_array($gl->id, old('grade_level_ids', $linkedGradeLevelIds ?? []));
                                $isElective = in_array($gl->id, old('elective_grades', $electiveGradeLevelIds ?? []));
                            @endphp
                            <tr>
                                <td class="ps-2 fw-semi-bold">{{ $gl->name }}</td>
                                <td class="text-center">
                                    <input class="form-check-input" type="checkbox" name="grade_level_ids[]" value="{{ $gl->id }}" {{ $isLinked ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input class="form-check-input" type="checkbox" name="elective_grades[]" value="{{ $gl->id }}" {{ $isElective ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
