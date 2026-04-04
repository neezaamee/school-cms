<div class="row g-3">
    <div class="col-md-9">
        <label class="form-label" for="name">Grade Scale Name <span class="text-danger">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="e.g. Standard 8-Point Scale" value="{{ old('name', $gradeScale->name ?? '') }}" required />
    </div>
    <div class="col-md-3">
        <label class="form-label" for="is_default">Is Default Scale?</label>
        <div class="form-check form-switch mt-1">
            <input class="form-check-input" id="is_default" name="is_default" type="checkbox" value="1" {{ old('is_default', $gradeScale->is_default ?? false) ? 'checked' : '' }} />
            <label class="form-check-label" for="is_default">Active Default</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description (Optional)</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $gradeScale->description ?? '') }}</textarea>
    </div>

    <div class="col-12 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Grade Rules (Range-based)</h6>
            <button class="btn btn-falcon-default btn-xs" type="button" @click="addRow()">
                <span class="fas fa-plus me-1"></span>Add Rule Row
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="bg-body-secondary">
                    <tr class="text-nowrap">
                        <th style="width: 15%">Grade Name</th>
                        <th style="width: 15%">Min %</th>
                        <th style="width: 15%">Max %</th>
                        <th style="width: 15%">Grade Point</th>
                        <th>Remarks / Description</th>
                        <th class="text-center" style="width: 5%"></th>
                    </tr>
                </thead>
                <tbody id="grade-rules-body">
                    {{-- Alpine.js or manual JS will manage this --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let rowCount = 0;
    const body = document.getElementById('grade-rules-body');

    function addRow(data = {}) {
        const tr = document.createElement('tr');
        tr.id = `row-${rowCount}`;
        tr.innerHTML = `
            <td><input class="form-control form-control-sm" name="details[${rowCount}][name]" value="${data.name || ''}" placeholder="A+" required /></td>
            <td><input class="form-control form-control-sm text-center" type="number" step="0.01" name="details[${rowCount}][min_score]" value="${data.min_score || '0'}" required /></td>
            <td><input class="form-control form-control-sm text-center" type="number" step="0.01" name="details[${rowCount}][max_score]" value="${data.max_score || '100'}" required /></td>
            <td><input class="form-control form-control-sm text-center" type="number" step="0.01" name="details[${rowCount}][point]" value="${data.point || '0.00'}" required /></td>
            <td><input class="form-control form-control-sm" name="details[${rowCount}][remarks]" value="${data.remarks || ''}" placeholder="Excellent performance" /></td>
            <td class="text-center"><button class="btn btn-link text-danger p-0" type="button" onclick="removeRow(${rowCount})"><span class="fas fa-times-circle"></span></button></td>
        `;
        body.appendChild(tr);
        rowCount++;
    }

    function removeRow(id) {
        document.getElementById(`row-${id}`).remove();
    }

    @if(isset($gradeScale) && $gradeScale->details->count() > 0)
        @foreach($gradeScale->details as $detail)
            addRow({
                name: "{{ $detail->name }}",
                min_score: "{{ $detail->min_score }}",
                max_score: "{{ $detail->max_score }}",
                point: "{{ $detail->point }}",
                remarks: "{{ $detail->remarks }}"
            });
        @endforeach
    @else
        // Add 2 initial rows for a better UX
        addRow({name: 'A', min_score: '80', max_score: '100', point: '4.00', remarks: 'Excellent'});
        addRow({name: 'F', min_score: '0', max_score: '39.99', point: '0.00', remarks: 'Fail'});
    @endif

    // Bind the "Add Row" button to our JS (override the alpine mock in HTML)
    document.querySelector('button[click="addRow()"]').onclick = () => addRow();
</script>
@endpush
