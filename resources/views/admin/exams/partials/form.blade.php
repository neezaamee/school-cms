<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="exam_term_id">Examination Term <span class="text-danger">*</span></label>
        <select class="form-select @error('exam_term_id') is-invalid @enderror" id="exam_term_id" name="exam_term_id" required>
            <option value="">Select Term</option>
            @foreach($examTerms as $term)
                <option value="{{ $term->id }}" {{ old('exam_term_id', $exam->exam_term_id ?? '') == $term->id ? 'selected' : '' }}>{{ $term->name }} ({{ $term->session_year }})</option>
            @endforeach
        </select>
        @error('exam_term_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="name">Exam Name <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. Midterm Quiz 1" value="{{ old('name', $exam->name ?? '') }}" required />
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="grade_level_id">Grade Level <span class="text-danger">*</span></label>
        <select class="form-select @error('grade_level_id') is-invalid @enderror" id="grade_level_id" name="grade_level_id" required>
            <option value="">Select Grade</option>
            @foreach($gradeLevels as $grade)
                <option value="{{ $grade->id }}" {{ old('grade_level_id', $exam->grade_level_id ?? '') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
            @endforeach
        </select>
        @error('grade_level_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="subject_id">Subject <span class="text-danger">*</span></label>
        <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
            <option value="">Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" data-grade="{{ $subject->grade_level_id }}" {{ old('subject_id', $exam->subject_id ?? '') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>
        @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label" for="exam_date">Exam Date</label>
        <input class="form-control datetimepicker mask-date @error('exam_date') is-invalid @enderror" id="exam_date" name="exam_date" type="text" placeholder="dd/mm/yyyy" data-options='{"disableMobile":true,"dateFormat":"d/m/Y"}' value="{{ old('exam_date', isset($exam) && $exam->exam_date ? $exam->exam_date->format('d/m/Y') : '') }}" />
        @error('exam_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="total_marks">Total Marks <span class="text-danger">*</span></label>
        <input class="form-control @error('total_marks') is-invalid @enderror" id="total_marks" name="total_marks" type="number" step="0.01" value="{{ old('total_marks', $exam->total_marks ?? 100) }}" required />
        @error('total_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="passing_marks">Passing Marks <span class="text-danger">*</span></label>
        <input class="form-control @error('passing_marks') is-invalid @enderror" id="passing_marks" name="passing_marks" type="number" step="0.01" value="{{ old('passing_marks', $exam->passing_marks ?? 33) }}" required />
        @error('passing_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="weightage">Term Weightage (%) <span class="text-danger">*</span></label>
        <input class="form-control @error('weightage') is-invalid @enderror" id="weightage" name="weightage" type="number" step="0.01" value="{{ old('weightage', $exam->weightage ?? 100) }}" required />
        <small class="text-500">Contribution to final term result (0-100%)</small>
        @error('weightage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const gradeSelect = $('#grade_level_id');
        const subjectSelect = $('#subject_id');
        const allSubjects = subjectSelect.find('option').clone();

        function filterSubjects() {
            const gradeId = gradeSelect.val();
            const currentSubId = subjectSelect.val();

            subjectSelect.find('option:not(:first)').remove();
            
            allSubjects.each(function() {
                const opt = $(this);
                if (opt.val() === '') return;
                
                if (!gradeId || opt.data('grade') == gradeId) {
                    subjectSelect.append(opt);
                }
            });

            // Reselect previously selected value if it still exists
            subjectSelect.val(currentSubId);
        }

        gradeSelect.on('change', filterSubjects);
        
        // Initial filter
        if(gradeSelect.val()) {
            filterSubjects();
        }
    });
</script>
@endpush
