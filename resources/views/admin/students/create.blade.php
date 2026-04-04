@extends('layouts.admin')

@section('content')
@php
    $isUrdu = request()->get('lang') === 'ur';
    $dir = $isUrdu ? 'rtl' : 'ltr';
    $align = $isUrdu ? 'text-end' : 'text-start';
@endphp

<div class="card mb-3" dir="{{ $dir }}" x-data="enrollmentForm()">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $isUrdu ? 'طالب علم کا داخلہ فارم' : 'Student Enrollment Form' }}</h5>
        <div>
            @if($isUrdu)
                <a href="{{ route('admin.students.create') }}" class="btn btn-sm btn-outline-primary">English Mode</a>
            @else
                <a href="{{ route('admin.students.create', ['lang' => 'ur']) }}" class="btn btn-sm btn-outline-primary">اردو موڈ</a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                {{-- 1. Student Profile --}}
                <div class="col-12">
                    <div class="border-bottom pb-2 mb-3">
                        <h6 class="text-primary fw-bold"><i class="fas fa-user-graduate me-2"></i> {{ $isUrdu ? '1. طالب علم کا پروفائل' : '1. Student Profile' }}</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'کیمپس' : 'Campus' }} <span class="text-danger">*</span></label>
                            <select name="campus_id" class="form-select" required>
                                <option value="">{{ $isUrdu ? 'کیمپس منتخب کریں' : 'Select Campus' }}</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'بی فارم نمبر' : 'B-Form Number' }} <span class="text-danger">*</span></label>
                            <input type="text" name="b_form" class="form-control mask-cnic" x-model="formData.b_form" @change="lookupStudent()" placeholder="00000-0000000-0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'نام (انگریزی)' : 'Full Name (English)' }} <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" x-model="formData.first_name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'رول نمبر' : 'Roll Number' }}</label>
                            <input type="text" name="roll_no" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'نام (اردو)' : 'Full Name (Urdu)' }}</label>
                            <input type="text" name="first_name_ur" class="form-control" x-model="formData.first_name_ur" dir="rtl">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'جنس' : 'Gender' }} <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" x-model="formData.gender" required>
                                <option value="">{{ $isUrdu ? 'منتخب کریں' : 'Select' }}</option>
                                <option value="Male">{{ $isUrdu ? 'مرد' : 'Male' }}</option>
                                <option value="Female">{{ $isUrdu ? 'عورت' : 'Female' }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'تاریخ پیدائش' : 'Date of Birth' }} <span class="text-danger">*</span></label>
                            <input type="text" name="dob" class="form-control mask-date" x-model="formData.dob" placeholder="dd/mm/yyyy" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'مذہب' : 'Religion' }}</label>
                            <input type="text" name="religion" class="form-control" x-model="formData.religion" placeholder="Islam">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'قومیت' : 'Nationality' }}</label>
                            <input type="text" name="nationality" class="form-control" x-model="formData.nationality">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'بلڈ گروپ' : 'Blood Group' }}</label>
                            <select name="blood_group" class="form-select" x-model="formData.blood_group">
                                <option value="">N/A</option>
                                <template x-for="bg in ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']">
                                    <option :value="bg" x-text="bg"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'حافظ قرآن' : 'Hafiz-e-Quran' }}</label>
                            <select name="is_hafiz_e_quran" class="form-select" x-model="formData.is_hafiz_e_quran">
                                <option value="0">{{ $isUrdu ? 'نہیں' : 'No' }}</option>
                                <option value="1">{{ $isUrdu ? 'جی ہاں' : 'Yes' }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'معذوری' : 'PWD (Disability)' }}</label>
                            <select name="is_pwd" class="form-select" x-model="formData.is_pwd">
                                <option value="0">{{ $isUrdu ? 'نہیں' : 'No' }}</option>
                                <option value="1">{{ $isUrdu ? 'جی ہاں' : 'Yes' }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'طبی نوٹس (الرجی وغیرہ)' : 'Medical Notes' }}</label>
                            <input type="text" name="medical_notes" class="form-control" x-model="formData.medical_notes">
                        </div>
                    </div>
                </div>

                {{-- 2. Parents Information --}}
                <div class="col-12">
                    <div class="border-bottom pb-2 mb-3">
                        <h6 class="text-primary fw-bold"><i class="fas fa-users me-2"></i> {{ $isUrdu ? '2. والدین کی معلومات' : '2. Parents Information' }}</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'والد کا شناختی کارڈ نمبر' : 'Father CNIC' }}</label>
                            <input type="text" name="father_cnic" class="form-control mask-cnic" x-model="formData.father_cnic" @change="lookupParent('father_cnic')">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'والد کا نام' : 'Father Name' }} <span class="text-danger">*</span></label>
                            <input type="text" name="father_name" class="form-control" x-model="formData.father_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'والد کا پیشہ' : 'Father Profession' }}</label>
                            <input type="text" name="father_profession" class="form-control" x-model="formData.father_profession">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'والدہ کا نام' : 'Mother Name' }}</label>
                            <input type="text" name="mother_name" class="form-control" x-model="formData.mother_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'والدہ کا پیشہ' : 'Mother Profession' }}</label>
                            <input type="text" name="mother_profession" class="form-control" x-model="formData.mother_profession">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'موبائل نمبر' : 'Mobile No' }} <span class="text-danger">*</span></label>
                            <input type="text" name="father_mobile" class="form-control mask-phone" x-model="formData.father_mobile" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'ای میل' : 'Email' }}</label>
                            <input type="email" name="father_email" class="form-control" x-model="formData.father_email">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'ماہانہ آمدنی' : 'Monthly Income' }}</label>
                            <input type="text" name="monthly_income" class="form-control" x-model="formData.monthly_income">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'گھر کا پتہ' : 'Home Address' }}</label>
                            <textarea name="home_address" class="form-control" rows="1" x-model="formData.home_address"></textarea>
                        </div>
                    </div>
                </div>

                {{-- 3. Guardian Information --}}
                <div class="col-12" x-data="{ splitGuardian: false }">
                    <div class="border-bottom pb-2 mb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-primary fw-bold"><i class="fas fa-user-shield me-2"></i> {{ $isUrdu ? '3. سرپرست کی معلومات' : '3. Guardian Information' }}</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" x-model="splitGuardian">
                            <label class="form-check-label ms-2 small">{{ $isUrdu ? 'والدین کے علاوہ' : 'Different from Parents' }}</label>
                        </div>
                    </div>
                    <div class="row g-3" x-show="splitGuardian" x-transition>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'سرپرست کا نام' : 'Guardian Name' }}</label>
                            <input type="text" name="guardian_name" class="form-control" x-model="formData.guardian_name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'رشتہ' : 'Relation' }}</label>
                            <input type="text" name="guardian_relation" class="form-control" x-model="formData.guardian_relation">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'شناختی کارڈ نمبر' : 'Guardian CNIC' }}</label>
                            <input type="text" name="guardian_cnic" class="form-control mask-cnic" x-model="formData.guardian_cnic">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'موبائل نمبر' : 'Guardian Mobile' }}</label>
                            <input type="text" name="guardian_mobile" class="form-control mask-phone" x-model="formData.guardian_mobile">
                        </div>
                    </div>
                </div>

                {{-- 4. Academic Details --}}
                <div class="col-12">
                    <div class="border-bottom pb-2 mb-3">
                        <h6 class="text-primary fw-bold"><i class="fas fa-graduation-cap me-2"></i> {{ $isUrdu ? '4. تعلیمی تفصیلات' : '4. Academic Details' }}</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'تاریخ داخلہ' : 'Admission Date' }}</label>
                            <input type="text" name="admission_date" class="form-control mask-date" value="{{ date('d/m/Y') }}" placeholder="dd/mm/yyyy">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'داخلہ نمبر' : 'Admission No' }} <span class="text-danger">*</span></label>
                            <input type="text" name="admission_no" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'کلاس' : 'Class' }} <span class="text-danger">*</span></label>
                            <select name="grade_level_id" class="form-select" @change="fetchSubjects($event.target.value)" required>
                                <option value="">{{ $isUrdu ? 'کلاس منتخب کریں' : 'Select Class' }}</option>
                                @foreach($gradeLevels as $gl)
                                    <option value="{{ $gl->id }}">{{ $gl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'سیکشن' : 'Section' }}</label>
                            <select name="section_id" class="form-select">
                                <option value="">Default</option>
                                @foreach($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'سیشن' : 'Session' }}</label>
                            <input type="text" name="session_year" class="form-control" value="2025-26">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'گروپ (سائنس / آرٹس)' : 'Group' }}</label>
                            <input type="text" name="academic_group" class="form-control" placeholder="e.g. Science">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'شفٹ' : 'Shift' }}</label>
                            <select name="shift" class="form-select">
                                <option value="Morning">Morning</option>
                                <option value="Evening">Evening</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 5. Academic System --}}
                <div class="col-12">
                    <div class="border-bottom pb-2 mb-3">
                        <h6 class="text-primary fw-bold"><i class="fas fa-university me-2"></i> {{ $isUrdu ? '5. تعلیمی نظام' : '5. Academic System' }}</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'بورڈ کی قسم' : 'Board Type' }}</label>
                            <input type="text" name="board_type" class="form-control" placeholder="BISE / Cambridge">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'ذریعہ تعلیم' : 'Medium' }}</label>
                            <select name="medium" class="form-select">
                                <option value="English">English</option>
                                <option value="Urdu">Urdu</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'پچھلی کلاس' : 'Previous Class' }}</label>
                            <input type="text" name="previous_class" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'پچھلا سکول' : 'Previous School' }}</label>
                            <input type="text" name="previous_school" class="form-control">
                        </div>
                        
                        <div class="col-12 mt-3" x-show="availableSubjects.length > 0">
                            <label class="form-label fw-bold">{{ $isUrdu ? 'مضامین کا انتخاب' : 'Subjects Selection' }}</label>
                            <div class="d-flex flex-wrap gap-2 border p-3 rounded bg-light">
                                <template x-for="subj in availableSubjects" :key="subj.id">
                                    <div class="form-check form-check-inline bg-white px-3 py-1 rounded border">
                                        <input class="form-check-input" type="checkbox" name="subjects_selected[]" :id="'subj'+subj.id" :value="subj.id" checked>
                                        <label class="form-check-label ms-1" :for="'subj'+subj.id" x-text="subj.name"></label>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 6. Fee Info --}}
                <div class="col-12">
                    <div class="border-bottom pb-2 mb-3">
                        <h6 class="text-primary fw-bold"><i class="fas fa-money-bill-wave me-2"></i> {{ $isUrdu ? '6. فیس کی معلومات' : '6. Fee Info' }}</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'فیس پلان' : 'Fee Plan' }}</label>
                            <select name="fee_plan_id" class="form-select">
                                <option value="">Standard Plan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ $isUrdu ? 'سکالرشپ' : 'Scholarship' }}</label>
                            <select name="scholarship_id" class="form-select">
                                <option value="">None</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ $isUrdu ? 'ٹرانسپورٹ فیس' : 'Transport Fee' }}</label>
                            <input type="number" name="transport_fee" class="form-control" value="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ $isUrdu ? 'رعایت (%)' : 'Discount (%)' }}</label>
                            <input type="number" name="discount_percentage" class="form-control" value="0">
                        </div>
                    </div>
                </div>

                {{-- 7. Documents --}}
                <div class="col-12">
                    <div class="border-bottom pb-2 mb-3">
                        <h6 class="text-primary fw-bold"><i class="fas fa-file-upload me-2"></i> {{ $isUrdu ? '7. دستاویزات' : '7. Documents' }}</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'تصویر' : 'Photo' }}</label>
                            <input type="file" name="photo" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'بی فارم / شناختی کارڈ' : 'B-Form / CNIC' }}</label>
                            <input type="file" name="attachments[bform]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'والد کا شناختی کارڈ' : 'Father CNIC' }}</label>
                            <input type="file" name="attachments[father_cnic]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ $isUrdu ? 'پچھلا رزلٹ کارڈ' : 'Previous Result' }}</label>
                            <input type="file" name="attachments[result]" class="form-control">
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-5 pt-3 border-top d-flex justify-content-between">
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary px-4">{{ $isUrdu ? 'منسوخ کریں' : 'Cancel' }}</a>
                <button type="submit" class="btn btn-primary px-5">{{ $isUrdu ? 'داخلہ مکمل کریں' : 'Complete Enrollment' }}</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function enrollmentForm() {
    return {
        formData: {
            b_form: '',
            first_name: '',
            first_name_ur: '',
            gender: '',
            dob: '{{ date("d/m/Y") }}',
            religion: 'Islam',
            nationality: 'Pakistani',
            blood_group: '',
            is_hafiz_e_quran: '0',
            is_pwd: '0',
            medical_notes: '',
            father_cnic: '',
            father_name: '',
            father_profession: '',
            mother_name: '',
            mother_profession: '',
            father_mobile: '',
            father_email: '',
            monthly_income: '',
            home_address: ''
        },
        availableSubjects: [],
        lookupStudent() {
            if (this.formData.b_form.length < 5) return;
            axios.get('{{ route("admin.students.search-bform") }}', { params: { bform: this.formData.b_form } })
                .then(res => {
                    if (res.data.found) {
                        const s = res.data.student;
                        this.formData.first_name = s.first_name;
                        this.formData.first_name_ur = s.first_name_ur;
                        this.formData.gender = s.gender;
                        // Format received date from Laravel (Y-m-d) to d/m/Y
                        if(s.dob) {
                            const dateParts = s.dob.split('-');
                            this.formData.dob = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
                        } else {
                            this.formData.dob = '';
                        }
                        this.formData.religion = s.religion;
                        this.formData.nationality = s.nationality;
                        this.formData.blood_group = s.blood_group;
                        this.formData.is_hafiz_e_quran = s.is_hafiz_e_quran ? '1' : '0';
                        this.formData.is_pwd = s.is_pwd ? '1' : '0';
                        this.formData.medical_notes = s.medical_notes;
                        toastr.info('Existing student found. Details auto-filled.');
                    }
                });
        },
        lookupParent(type) {
            let value = this.formData[type];
            if (value.length < 5) return;
            axios.get('{{ route("admin.students.search-parent-cnic") }}', { params: { cnic: value } })
                .then(res => {
                    if (res.data.found) {
                        const p = res.data.parent;
                        this.formData.father_name = p.father_name;
                        this.formData.father_profession = p.father_profession;
                        this.formData.mother_name = p.mother_name;
                        this.formData.mother_profession = p.mother_profession;
                        this.formData.father_mobile = p.father_mobile;
                        this.formData.father_email = p.father_email;
                        this.formData.monthly_income = p.monthly_income;
                        this.formData.home_address = p.home_address;
                        toastr.info('Parent records found via CNIC. Details auto-filled.');
                    }
                });
        },
        fetchSubjects(classId) {
            if (!classId) return;
            axios.get('{{ route("admin.students.subjects-by-class") }}', { params: { class_id: classId } })
                .then(res => {
                    this.availableSubjects = res.data;
                });
        }
    }
}
</script>
@endpush

@endsection
