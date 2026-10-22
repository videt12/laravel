@extends('layouts.app')

@section('title', 'កែសម្រួលព័ត៌មានសិស្ស — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">សិស្ស</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.show', $student->id) }}" class="text-decoration-none text-muted">{{ $student->admission_number }}</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កែសម្រួលព័ត៌មានសិស្ស</li>
    </ol>
</nav>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header d-flex align-items-start justify-content-between mb-3" style="max-width: 980px; margin-left: auto; margin-right: auto;">
    <div>
        <h1 class="page-title">កែសម្រួលព័ត៌មានសិស្ស (Edit Student)</h1>
        <p class="page-subtitle mb-0">ធ្វើបច្ចុប្បន្នភាពព័ត៌មានរបស់សិស្ស</p>
    </div>
</div>

{{-- Validation Summary --}}
@if($errors->any())
    <div class="alert-card alert-card-danger mb-3" style="max-width: 980px; margin-left: auto; margin-right: auto;">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <strong>សូមពិនិត្យ និងកែតម្រូវកំហុសឆ្គងខាងក្រោម មុនពេលរក្សាទុក៖</strong>
        <ul class="mb-0 mt-2 ps-4">
            @foreach($errors->all() as $error)
                <li style="font-size:.875rem">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data" novalidate id="studentForm">
    @csrf
    @method('PUT')

    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 980px;">

            {{-- 1. Basic Information --}}
            <div class="form-card mb-3">
                <div class="form-card-header bg-white border-bottom-0 pb-0">
                    <div class="d-flex align-items-center">
                        <span class="step-badge">1</span>
                        <div>
                            <h5 class="mb-0 section-heading">ព័ត៌មានមូលដ្ឋាន <span class="section-subheading">(Basic Information)</span></h5>
                        </div>
                    </div>
                </div>
                <div class="form-card-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-3 text-center d-flex flex-column align-items-center">
                            <label class="form-label-custom d-block w-100 text-start">
                                <i class="bi bi-person-bounding-box me-1 text-warning"></i>រូបថត (Photo)&nbsp;<span class="text-danger">*</span>
                            </label>
                            
                            @php
                                $hasExistingPhoto = $student->studentProfile && $student->studentProfile->photo;
                                $existingPhotoUrl = $hasExistingPhoto ? ($student->photo_url ?? Storage::url($student->studentProfile->photo)) : '';
                            @endphp

                            <div class="photo-card-box position-relative" onclick="document.getElementById('photo_input').click()" title="ចុចដើម្បីជ្រើសរើសរូបថត">
                                <div class="photo-inner-wrapper">
                                    <img id="photo_preview" src="{{ $existingPhotoUrl }}" alt="Preview" class="photo-img-tag {{ $hasExistingPhoto ? '' : 'd-none' }}" onerror="this.classList.add('d-none'); document.getElementById('photo_placeholder')?.classList.remove('d-none');">
                                    <div id="photo_placeholder" class="photo-placeholder-wrap {{ $hasExistingPhoto ? 'd-none' : '' }}">
                                        <div class="photo-icon-avatar">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="photo-hint-main">ចុចដើម្បីផ្ទុកឡើង</div>
                                        <div class="photo-hint-sub">ទំហំ 3x4 ឬ 4x6</div>
                                    </div>
                                    
                                    {{-- Hover overlay for changing photo --}}
                                    <div class="photo-hover-mask">
                                        <i class="bi bi-camera-fill fs-4 mb-1"></i>
                                        <span>ផ្លាស់ប្តូររូបថត</span>
                                    </div>
                                </div>

                                {{-- Floating badge button --}}
                                <div class="photo-floating-badge" title="ជ្រើសរើសរូបថត">
                                    <i class="bi bi-camera-fill"></i>
                                </div>
                            </div>

                            <input type="file" name="photo" id="photo_input" class="d-none @error('photo') is-invalid @enderror" accept="image/png, image/jpeg, image/webp" onchange="previewImage(this)">
                            <input type="hidden" name="remove_photo" id="remove_photo_hidden" value="0">
                            
                            <div class="d-flex align-items-center justify-content-center gap-2 mt-2 w-100">
                                <button type="button" class="btn-photo-action" onclick="document.getElementById('photo_input').click()">
                                    <i class="bi bi-upload"></i>
                                    <span>{{ $hasExistingPhoto ? 'ប្តូររូបថ្មី' : 'ជ្រើសរើសរូប' }}</span>
                                </button>
                                <button type="button" id="btn_remove_photo" class="btn-photo-remove {{ $hasExistingPhoto ? '' : 'd-none' }}" onclick="removePhoto()" title="លុបរូបថត">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                            
                            <div class="text-muted mt-1" style="font-size: 0.72rem;">JPG, PNG (អតិបរមា 2MB)</div>
                            @error('photo')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                            <div id="duplicate_photo_feedback" class="text-danger mt-1 d-none" style="font-size: 0.78rem;"></div>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-custom">អត្តលេខសិស្ស (Student ID)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted" title="កំណត់ដោយស្វ័យប្រវត្តិ">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control form-control-custom bg-light border-start-0 fw-semibold text-primary"
                                               value="{{ $student->admission_number }}"
                                               readonly
                                               tabindex="-1"
                                               title="អត្តលេខសិស្សមិនអាចកែប្រែបានទេ">
                                    </div>
                                    <div class="form-text text-muted" style="font-size:.75rem;">
                                        <i class="bi bi-info-circle me-1"></i>កំណត់ដោយប្រព័ន្ធ — មិនអាចកែប្រែបានទេ
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">ថ្ងៃចូលរៀន (Admission Date) <span class="text-danger">*</span></label>
                                    <input type="date" name="admission_date" class="form-control form-control-custom @error('admission_date') is-invalid @enderror" value="{{ old('admission_date', $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') : '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom text-nowrap">នាមត្រកូល (ភាសាខ្មែរ)&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text" name="last_name_khmer" id="last_name_khmer" class="form-control form-control-custom @error('last_name_khmer') is-invalid @enderror" value="{{ old('last_name_khmer', $student->studentProfile->last_name_khmer ?? '') }}" placeholder="បញ្ចូលនាមត្រកូល">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom text-nowrap">នាមខ្លួន (ភាសាខ្មែរ)&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text" name="first_name_khmer" id="first_name_khmer" class="form-control form-control-custom @error('first_name_khmer') is-invalid @enderror" value="{{ old('first_name_khmer', $student->studentProfile->first_name_khmer ?? '') }}" placeholder="បញ្ចូលនាមខ្លួន">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">នាមខ្លួនជាឡាតាំង (First Name)</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control form-control-custom @error('first_name') is-invalid @enderror" value="{{ old('first_name', $student->studentProfile->first_name ?? '') }}" placeholder="បញ្ចូលនាមខ្លួន (អក្សរឡាតាំង)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">នាមត្រកូលជាឡាតាំង (Last Name)</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control form-control-custom @error('last_name') is-invalid @enderror" value="{{ old('last_name', $student->studentProfile->last_name ?? '') }}" placeholder="បញ្ចូលនាមត្រកូល (អក្សរឡាតាំង)">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label-custom">ភេទ (Gender) <span class="text-danger">*</span></label>
                            <select name="gender" id="student_gender" class="form-select form-control-custom @error('gender') is-invalid @enderror" required>
                                 <option value="">ជ្រើសរើសភេទ</option>
                                 <option value="Male" {{ old('gender', $student->studentProfile->gender ?? '') == 'Male' ? 'selected' : '' }}>ប្រុស (Male)</option>
                                 <option value="Female" {{ old('gender', $student->studentProfile->gender ?? '') == 'Female' ? 'selected' : '' }}>ស្រី (Female)</option>
                             </select>
                         </div>
                         <div class="col-md-4 mt-3">
                             <label class="form-label-custom">ថ្ងៃខែឆ្នាំកំណើត (Date of Birth) <span class="text-danger">*</span></label>
                             <input type="date" name="dob" id="student_dob" class="form-control form-control-custom @error('dob') is-invalid @enderror" value="{{ old('dob', ($student->studentProfile->dob ?? false) ? \Carbon\Carbon::parse($student->studentProfile->dob)->format('Y-m-d') : '') }}" required>
                             <div id="duplicate_dob_feedback" class="text-danger small mt-1 d-none"></div>
                         </div>
                         <div class="col-md-4 mt-3">
                             <label class="form-label-custom">សញ្ជាតិ (Nationality)</label>
                             <select name="nationality" class="form-select form-control-custom">
                                 <option value="">ជ្រើសរើសសញ្ជាតិ</option>
                                 <option value="Cambodian" {{ old('nationality', $student->studentProfile->nationality ?? '') == 'Cambodian' ? 'selected' : '' }}>ខ្មែរ (Cambodian)</option>
                                 <option value="Other" {{ old('nationality', $student->studentProfile->nationality ?? '') == 'Other' ? 'selected' : '' }}>ផ្សេងៗ (Other)</option>
                             </select>
                         </div>

                        {{-- 2. Contact & Address --}}
                        <div class="col-12 mt-3 pt-2.5 border-top">
                            <div class="d-flex align-items-center mb-3">
                                <span class="step-badge">2</span>
                                <div>
                                    <h5 class="mb-0 section-heading">ទំនាក់ទំនង និង អាសយដ្ឋាន <span class="section-subheading">(Contact & Address)</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">អ៊ីមែល (Email)</label>
                            <input type="email" name="email" id="student_email" class="form-control form-control-custom @error('email') is-invalid @enderror" value="{{ old('email', $student->user->email ?? '') }}" placeholder="បញ្ចូលអ៊ីមែល">
                            <div id="duplicate_email_feedback" class="text-danger small mt-1 d-none"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">លេខទូរស័ព្ទ (Phone)</label>
                            <input type="text" name="phone" class="form-control form-control-custom @error('phone') is-invalid @enderror" value="{{ old('phone', $student->studentProfile->phone ?? '') }}" placeholder="បញ្ចូលលេខទូរស័ព្ទ">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">លេខផ្លូវ</label>
                            <input type="text" name="address_line_1" class="form-control form-control-custom @error('address_line_1') is-invalid @enderror" value="{{ old('address_line_1', $student->studentAddresse->address_line_1 ?? '') }}" placeholder="បញ្ចូលលេខផ្លូវ...">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label-custom">រាជធានី / ខេត្ត <span class="text-danger">*</span></label>
                            <select name="province" id="province" class="form-select form-control-custom @error('province') is-invalid @enderror" required>
                                <option value="">ជ្រើសរើសរាជធានី-ខេត្ត</option>
                                <option value="Phnom Penh" {{ old('province', $student->studentAddresse->province ?? '') == 'Phnom Penh' ? 'selected' : '' }}>រាជធានីភ្នំពេញ</option>
                                <option value="Banteay Meanchey" {{ old('province', $student->studentAddresse->province ?? '') == 'Banteay Meanchey' ? 'selected' : '' }}>ខេត្តបន្ទាយមានជ័យ</option>
                                <option value="Battambang" {{ old('province', $student->studentAddresse->province ?? '') == 'Battambang' ? 'selected' : '' }}>ខេត្តបាត់ដំបង</option>
                                <option value="Kampong Cham" {{ old('province', $student->studentAddresse->province ?? '') == 'Kampong Cham' ? 'selected' : '' }}>ខេត្តកំពង់ចាម</option>
                                <option value="Kampong Chhnang" {{ old('province', $student->studentAddresse->province ?? '') == 'Kampong Chhnang' ? 'selected' : '' }}>ខេត្តកំពង់ឆ្នាំង</option>
                                <option value="Kampong Speu" {{ old('province', $student->studentAddresse->province ?? '') == 'Kampong Speu' ? 'selected' : '' }}>ខេត្តកំពង់ស្ពឺ</option>
                                <option value="Kampong Thom" {{ old('province', $student->studentAddresse->province ?? '') == 'Kampong Thom' ? 'selected' : '' }}>ខេត្តកំពង់ធំ</option>
                                <option value="Kampot" {{ old('province', $student->studentAddresse->province ?? '') == 'Kampot' ? 'selected' : '' }}>ខេត្តកំពត</option>
                                <option value="Kandal" {{ old('province', $student->studentAddresse->province ?? '') == 'Kandal' ? 'selected' : '' }}>ខេត្តកណ្តាល</option>
                                <option value="Koh Kong" {{ old('province', $student->studentAddresse->province ?? '') == 'Koh Kong' ? 'selected' : '' }}>ខេត្តកោះកុង</option>
                                <option value="Kratie" {{ old('province', $student->studentAddresse->province ?? '') == 'Kratie' ? 'selected' : '' }}>ខេត្តក្រចេះ</option>
                                <option value="Mondulkiri" {{ old('province', $student->studentAddresse->province ?? '') == 'Mondulkiri' ? 'selected' : '' }}>ខេត្តមណ្ឌលគីរី</option>
                                <option value="Oddar Meanchey" {{ old('province', $student->studentAddresse->province ?? '') == 'Oddar Meanchey' ? 'selected' : '' }}>ខេត្តឧត្តរមានជ័យ</option>
                                <option value="Pailin" {{ old('province', $student->studentAddresse->province ?? '') == 'Pailin' ? 'selected' : '' }}>ខេត្តប៉ៃលិន</option>
                                <option value="Pursat" {{ old('province', $student->studentAddresse->province ?? '') == 'Pursat' ? 'selected' : '' }}>ខេត្តពោធិ៍សាត់</option>
                                <option value="Preah Vihear" {{ old('province', $student->studentAddresse->province ?? '') == 'Preah Vihear' ? 'selected' : '' }}>ខេត្តព្រះវិហារ</option>
                                <option value="Prey Veng" {{ old('province', $student->studentAddresse->province ?? '') == 'Prey Veng' ? 'selected' : '' }}>ខេត្តព្រៃវែង</option>
                                <option value="Ratanakiri" {{ old('province', $student->studentAddresse->province ?? '') == 'Ratanakiri' ? 'selected' : '' }}>ខេត្តរតនគីរី</option>
                                <option value="Siem Reap" {{ old('province', $student->studentAddresse->province ?? '') == 'Siem Reap' ? 'selected' : '' }}>ខេត្តសៀមរាប</option>
                                <option value="Preah Sihanouk" {{ old('province', $student->studentAddresse->province ?? '') == 'Preah Sihanouk' ? 'selected' : '' }}>ខេត្តព្រះសីហនុ</option>
                                <option value="Stung Treng" {{ old('province', $student->studentAddresse->province ?? '') == 'Stung Treng' ? 'selected' : '' }}>ខេត្តស្ទឹងត្រែង</option>
                                <option value="Svay Rieng" {{ old('province', $student->studentAddresse->province ?? '') == 'Svay Rieng' ? 'selected' : '' }}>ខេត្តស្វាយរៀង</option>
                                <option value="Takeo" {{ old('province', $student->studentAddresse->province ?? '') == 'Takeo' ? 'selected' : '' }}>ខេត្តតាកែវ</option>
                                <option value="Kep" {{ old('province', $student->studentAddresse->province ?? '') == 'Kep' ? 'selected' : '' }}>ខេត្តកែប</option>
                                <option value="Tboung Khmum" {{ old('province', $student->studentAddresse->province ?? '') == 'Tboung Khmum' ? 'selected' : '' }}>ខេត្តត្បូងឃ្មុំ</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">ស្រុក / ខណ្ឌ <span class="text-danger">*</span></label>
                            <select name="district" id="district" class="form-select form-control-custom @error('district') is-invalid @enderror" data-selected="{{ old('district', $student->studentAddresse->district ?? '') }}" required>
                                <option value="">ជ្រើសខេត្តជាមុន</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">ឃុំ / សង្កាត់ <span class="text-danger">*</span></label>
                            <select name="commune" id="commune" class="form-select form-control-custom @error('commune') is-invalid @enderror" data-selected="{{ old('commune', $student->studentAddresse->commune ?? '') }}" required>
                                <option value="">ជ្រើសស្រុកជាមុន</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">ភូមិ <span class="text-danger">*</span></label>
                            <select name="village" id="village" class="form-select form-control-custom @error('village') is-invalid @enderror" data-selected="{{ old('village', $student->studentAddresse->village ?? '') }}" required>
                                <option value="">ជ្រើសឃុំជាមុន</option>
                            </select>
                        </div>


                        @php
                            $father = $student->guardians->first(fn($g) => in_array(strtolower($g->pivot->relationship ?? ''), ['father', 'ឪពុក']));
                            $mother = $student->guardians->first(fn($g) => in_array(strtolower($g->pivot->relationship ?? ''), ['mother', 'ម្តាយ']));
                            $otherGuardian = $student->guardians->first(fn($g) => !in_array(strtolower($g->pivot->relationship ?? ''), ['father', 'mother', 'ឪពុក', 'ម្តាយ']));
                            $primaryGuardian = $student->guardians->firstWhere('pivot.is_primary', true) ?? $student->guardians->first();

                            $primaryRole = 'father';
                            if ($primaryGuardian) {
                                $rel = strtolower($primaryGuardian->pivot->relationship ?? '');
                                if (in_array($rel, ['mother', 'ម្តាយ'])) {
                                    $primaryRole = 'mother';
                                } elseif (!in_array($rel, ['father', 'ឪពុក'])) {
                                    $primaryRole = 'other';
                                }
                            }

                            $guardianAddress = $primaryGuardian?->address ?? ($father?->address ?? ($mother?->address ?? ''));
                            $gVillage = ''; $gCommune = ''; $gDistrict = ''; $gProvince = '';
                            if ($guardianAddress) {
                                if (preg_match('/ភូមិ\s*([^,]+)/u', $guardianAddress, $m)) $gVillage = trim($m[1]);
                                if (preg_match('/ឃុំ\/សង្កាត់\s*([^,]+)/u', $guardianAddress, $m)) $gCommune = trim($m[1]);
                                if (preg_match('/ស្រុក\/ខណ្ឌ\s*([^,]+)/u', $guardianAddress, $m)) $gDistrict = trim($m[1]);
                                foreach (['Phnom Penh', 'Banteay Meanchey', 'Battambang', 'Kampong Cham', 'Kampong Chhnang', 'Kampong Speu', 'Kampong Thom', 'Kampot', 'Kandal', 'Kep', 'Koh Kong', 'Kratie', 'Mondulkiri', 'Oddar Meanchey', 'Pailin', 'Preah Vihear', 'Preah Sihanouk', 'Prey Veng', 'Pursat', 'Ratanakiri', 'Siem Reap', 'Stung Treng', 'Svay Rieng', 'Takeo', 'Tboung Khmum'] as $prov) {
                                    if (stripos($guardianAddress, $prov) !== false) {
                                        $gProvince = $prov;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        {{-- 3. Guardian Information --}}
                        <div class="col-12 mt-3 pt-2.5 border-top">
                            <div class="d-flex align-items-center mb-3">
                                <span class="step-badge">3</span>
                                <div>
                                    <h5 class="mb-0 section-heading">ព័ត៌មានឪពុកម្តាយ និងអាណាព្យាបាល <span class="section-subheading">(Parents & Guardian Information)</span></h5>
                                </div>
                            </div>
                        </div>

                        {{-- 1. Father's Information --}}
                        <div class="col-12">
                            <div class="p-2 px-3 rounded-2 fw-semibold text-dark d-flex align-items-center gap-2" style="background: #f0fdf4; border-left: 3px solid #16a34a; font-size: 0.83rem;">
                                <i class="bi bi-gender-male text-success"></i>
                                <span>ព័ត៌មានឪពុក (Father's Information)</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">ឈ្មោះឪពុក (Father) <span class="text-danger">*</span></label>
                            <input type="text" name="father_name" id="father_name" class="form-control form-control-custom @error('father_name') is-invalid @enderror" value="{{ old('father_name', $father?->user?->name ?? '') }}" placeholder="បញ្ចូលឈ្មោះឪពុក">
                            @error('father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-custom">អាយុ (Age) <span class="text-danger">*</span></label>
                            <input type="number" name="father_age" id="father_age" class="form-control form-control-custom @error('father_age') is-invalid @enderror" value="{{ old('father_age', $father?->age ?? '') }}" placeholder="អាយុ" min="15" max="120">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">មុខរបរ (Occupation) <span class="text-danger">*</span></label>
                            <input type="text" name="father_occupation" id="father_occupation" class="form-control form-control-custom @error('father_occupation') is-invalid @enderror" value="{{ old('father_occupation', $father?->occupation ?? '') }}" placeholder="បញ្ចូលមុខរបរ">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">លេខទូរស័ព្ទ (Phone) <span class="text-danger">*</span></label>
                            <input type="text" name="father_phone" id="father_phone" class="form-control form-control-custom @error('father_phone') is-invalid @enderror" value="{{ old('father_phone', $father?->guardian_contacts?->first()?->phone ?? '') }}" placeholder="បញ្ចូលលេខទូរស័ព្ទ">
                            @error('father_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. Mother's Information --}}
                        <div class="col-12 mt-2">
                            <div class="p-2 px-3 rounded-2 fw-semibold text-dark d-flex align-items-center gap-2" style="background: #fdf2f8; border-left: 3px solid #ec4899; font-size: 0.83rem;">
                                <i class="bi bi-gender-female text-danger"></i>
                                <span>ព័ត៌មានម្តាយ (Mother's Information)</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">ឈ្មោះម្តាយ (Mother) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name" id="mother_name" class="form-control form-control-custom @error('mother_name') is-invalid @enderror" value="{{ old('mother_name', $mother?->user?->name ?? '') }}" placeholder="បញ្ចូលឈ្មោះម្តាយ">
                            @error('mother_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-custom">អាយុ (Age) <span class="text-danger">*</span></label>
                            <input type="number" name="mother_age" id="mother_age" class="form-control form-control-custom @error('mother_age') is-invalid @enderror" value="{{ old('mother_age', $mother?->age ?? '') }}" placeholder="អាយុ" min="15" max="120">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">មុខរបរ (Occupation) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_occupation" id="mother_occupation" class="form-control form-control-custom @error('mother_occupation') is-invalid @enderror" value="{{ old('mother_occupation', $mother?->occupation ?? '') }}" placeholder="បញ្ចូលមុខរបរ">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">លេខទូរស័ព្ទ (Phone) <span class="text-danger">*</span></label>
                            <input type="text" name="mother_phone" id="mother_phone" class="form-control form-control-custom @error('mother_phone') is-invalid @enderror" value="{{ old('mother_phone', $mother?->guardian_contacts?->first()?->phone ?? '') }}" placeholder="បញ្ចូលលេខទូរស័ព្ទ">
                            @error('mother_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. Primary Guardian Selection --}}
                        <div class="col-12 mt-2">
                            <div class="p-2 px-3 rounded-2 fw-semibold text-dark d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #f8fafc; border-left: 3px solid #64748b; font-size: 0.83rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-check text-secondary"></i>
                                    <span>អាណាព្យាបាលចម្បង និងទំនាក់ទំនង (Primary Guardian)</span>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-muted small fw-normal">កំណត់ជាអាណាព្យាបាលចម្បង៖</span>
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="radio" name="primary_guardian" id="primary_father" value="father" {{ old('primary_guardian', $primaryRole) == 'father' ? 'checked' : '' }} onchange="toggleOtherGuardianSection()">
                                        <label class="form-check-label fw-medium" for="primary_father" style="font-size: 0.78rem; cursor: pointer;">ឪពុក</label>
                                    </div>
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="radio" name="primary_guardian" id="primary_mother" value="mother" {{ old('primary_guardian', $primaryRole) == 'mother' ? 'checked' : '' }} onchange="toggleOtherGuardianSection()">
                                        <label class="form-check-label fw-medium" for="primary_mother" style="font-size: 0.78rem; cursor: pointer;">ម្តាយ</label>
                                    </div>
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="radio" name="primary_guardian" id="primary_other" value="other" {{ old('primary_guardian', $primaryRole) == 'other' ? 'checked' : '' }} onchange="toggleOtherGuardianSection()">
                                        <label class="form-check-label fw-medium" for="primary_other" style="font-size: 0.78rem; cursor: pointer;">អ្នកផ្សេង</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Other Guardian Fields (Shown if Other is selected) --}}
                        <div class="col-12 p-0 m-0" id="otherGuardianFields" style="{{ old('primary_guardian', $primaryRole) == 'other' ? '' : 'display: none;' }}">
                            <div class="row g-3 m-0 p-0">
                                <div class="col-md-6">
                                    <label class="form-label-custom">ឈ្មោះអាណាព្យាបាល (Guardian) <span class="text-danger">*</span></label>
                                    <input type="text" name="guardian_name" id="guardian_name" class="form-control form-control-custom" value="{{ old('guardian_name', $otherGuardian?->user?->name ?? '') }}" placeholder="បញ្ចូលឈ្មោះអាណាព្យាបាល">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">ទំនាក់ទំនង (Relationship) <span class="text-danger">*</span></label>
                                    <input type="text" name="guardian_relationship" id="guardian_relationship" class="form-control form-control-custom" value="{{ old('guardian_relationship', $otherGuardian?->pivot?->relationship ?? '') }}" placeholder="ឧ. ពូ, មីង, ជីដូន, ជីតា...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-custom">អាយុ (Age) <span class="text-danger">*</span></label>
                                    <input type="number" name="guardian_age" id="guardian_age" class="form-control form-control-custom" value="{{ old('guardian_age', $otherGuardian?->age ?? '') }}" placeholder="អាយុ" min="15" max="120">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">មុខរបរ (Occupation) <span class="text-danger">*</span></label>
                                    <input type="text" name="guardian_occupation" id="guardian_occupation" class="form-control form-control-custom" value="{{ old('guardian_occupation', $otherGuardian?->occupation ?? '') }}" placeholder="បញ្ចូលមុខរបរ">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label-custom">លេខទូរស័ព្ទ (Phone) <span class="text-danger">*</span></label>
                                    <input type="text" name="guardian_phone" id="guardian_phone" class="form-control form-control-custom" value="{{ old('guardian_phone', $otherGuardian?->guardian_contacts?->first()?->phone ?? '') }}" placeholder="បញ្ចូលលេខទូរស័ព្ទ">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-custom">អ៊ីមែល (Email)</label>
                                    <input type="email" name="guardian_email" id="guardian_email" class="form-control form-control-custom" value="{{ old('guardian_email', $otherGuardian?->user?->email ?? '') }}" placeholder="បញ្ចូលអ៊ីមែល">
                                </div>
                            </div>
                        </div>

                        {{-- General guardian email when father or mother is primary --}}
                        <div class="col-12" id="generalGuardianEmailCol" style="{{ old('primary_guardian', $primaryRole) == 'other' ? 'display: none;' : '' }}">
                            <label class="form-label-custom">អ៊ីមែលអាណាព្យាបាល (Guardian Email - ស្រេចចិត្ត)</label>
                            <input type="email" name="guardian_general_email" id="guardian_general_email" class="form-control form-control-custom" value="{{ old('guardian_general_email', old('guardian_email', ($primaryRole !== 'other' ? $primaryGuardian?->user?->email : '') ?? '')) }}" placeholder="បញ្ចូលអ៊ីមែលអាណាព្យាបាល (បើមាន)">
                        </div>

                        {{-- 4. Guardian Address (ភូមិ, ឃុំ, ស្រុក, ខេត្ត) --}}
                        <div class="col-12 mt-2">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label-custom mb-0"><i class="bi bi-geo-alt me-1 text-primary"></i>អាសយដ្ឋានអាណាព្យាបាល (Guardian Address)</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 fw-normal" style="font-size: 0.72rem; border-radius: 4px;" onclick="copyStudentAddressToGuardian()" title="ចម្លងអាសយដ្ឋានពីសិស្ស">
                                    <i class="bi bi-copy me-1 text-primary"></i>ដូចអាសយដ្ឋានសិស្ស
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">រាជធានី / ខេត្ត <span class="text-danger">*</span></label>
                            <select name="guardian_province" id="guardian_province" class="form-select form-control-custom" onchange="compileGuardianAddress()">
                                <option value="">ជ្រើសរើសរាជធានី-ខេត្ត</option>
                                <option value="Phnom Penh" {{ old('guardian_province', $gProvince ?: 'Phnom Penh') == 'Phnom Penh' ? 'selected' : '' }}>រាជធានីភ្នំពេញ</option>
                                <option value="Banteay Meanchey" {{ old('guardian_province', $gProvince) == 'Banteay Meanchey' ? 'selected' : '' }}>ខេត្តបន្ទាយមានជ័យ</option>
                                <option value="Battambang" {{ old('guardian_province', $gProvince) == 'Battambang' ? 'selected' : '' }}>ខេត្តបាត់ដំបង</option>
                                <option value="Kampong Cham" {{ old('guardian_province', $gProvince) == 'Kampong Cham' ? 'selected' : '' }}>ខេត្តកំពង់ចាម</option>
                                <option value="Kampong Chhnang" {{ old('guardian_province', $gProvince) == 'Kampong Chhnang' ? 'selected' : '' }}>ខេត្តកំពង់ឆ្នាំង</option>
                                <option value="Kampong Speu" {{ old('guardian_province', $gProvince) == 'Kampong Speu' ? 'selected' : '' }}>ខេត្តកំពង់ស្ពឺ</option>
                                <option value="Kampong Thom" {{ old('guardian_province', $gProvince) == 'Kampong Thom' ? 'selected' : '' }}>ខេត្តកំពង់ធំ</option>
                                <option value="Kampot" {{ old('guardian_province', $gProvince) == 'Kampot' ? 'selected' : '' }}>ខេត្តកំពត</option>
                                <option value="Kandal" {{ old('guardian_province', $gProvince) == 'Kandal' ? 'selected' : '' }}>ខេត្តកណ្តាល</option>
                                <option value="Kep" {{ old('guardian_province', $gProvince) == 'Kep' ? 'selected' : '' }}>ខេត្តកែប</option>
                                <option value="Koh Kong" {{ old('guardian_province', $gProvince) == 'Koh Kong' ? 'selected' : '' }}>ខេត្តកោះកុង</option>
                                <option value="Kratie" {{ old('guardian_province', $gProvince) == 'Kratie' ? 'selected' : '' }}>ខេត្តក្រចេះ</option>
                                <option value="Mondulkiri" {{ old('guardian_province', $gProvince) == 'Mondulkiri' ? 'selected' : '' }}>ខេត្តមណ្ឌលគិរី</option>
                                <option value="Oddar Meanchey" {{ old('guardian_province', $gProvince) == 'Oddar Meanchey' ? 'selected' : '' }}>ខេត្តឧត្តរមានជ័យ</option>
                                <option value="Pailin" {{ old('guardian_province', $gProvince) == 'Pailin' ? 'selected' : '' }}>ខេត្តប៉ៃលិន</option>
                                <option value="Preah Vihear" {{ old('guardian_province', $gProvince) == 'Preah Vihear' ? 'selected' : '' }}>ខេត្តព្រះវិហារ</option>
                                <option value="Preah Sihanouk" {{ old('guardian_province', $gProvince) == 'Preah Sihanouk' ? 'selected' : '' }}>ខេត្តព្រះសីហនុ</option>
                                <option value="Prey Veng" {{ old('guardian_province', $gProvince) == 'Prey Veng' ? 'selected' : '' }}>ខេត្តព្រៃវែង</option>
                                <option value="Pursat" {{ old('guardian_province', $gProvince) == 'Pursat' ? 'selected' : '' }}>ខេត្តពោធិ៍សាត់</option>
                                <option value="Ratanakiri" {{ old('guardian_province', $gProvince) == 'Ratanakiri' ? 'selected' : '' }}>ខេត្តរតនគិរី</option>
                                <option value="Siem Reap" {{ old('guardian_province', $gProvince) == 'Siem Reap' ? 'selected' : '' }}>ខេត្តសៀមរាប</option>
                                <option value="Stung Treng" {{ old('guardian_province', $gProvince) == 'Stung Treng' ? 'selected' : '' }}>ខេត្តស្ទឹងត្រែង</option>
                                <option value="Svay Rieng" {{ old('guardian_province', $gProvince) == 'Svay Rieng' ? 'selected' : '' }}>ខេត្តស្វាយរៀង</option>
                                <option value="Takeo" {{ old('guardian_province', $gProvince) == 'Takeo' ? 'selected' : '' }}>ខេត្តតាកែវ</option>
                                <option value="Tboung Khmum" {{ old('guardian_province', $gProvince) == 'Tboung Khmum' ? 'selected' : '' }}>ខេត្តត្បូងឃ្មុំ</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">ស្រុក / ខណ្ឌ <span class="text-danger">*</span></label>
                            <select name="guardian_district" id="guardian_district" class="form-select form-control-custom" data-selected="{{ old('guardian_district', $gDistrict) }}" onchange="compileGuardianAddress()">
                                <option value="">ជ្រើសខេត្តជាមុន</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">ឃុំ / សង្កាត់ <span class="text-danger">*</span></label>
                            <select name="guardian_commune" id="guardian_commune" class="form-select form-control-custom" data-selected="{{ old('guardian_commune', $gCommune) }}" onchange="compileGuardianAddress()">
                                <option value="">ជ្រើសស្រុកជាមុន</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">ភូមិ <span class="text-danger">*</span></label>
                            <select name="guardian_village" id="guardian_village" class="form-select form-control-custom" data-selected="{{ old('guardian_village', $gVillage) }}" onchange="compileGuardianAddress()">
                                <option value="">ជ្រើសឃុំជាមុន</option>
                            </select>
                        </div>
                        <input type="hidden" name="guardian_address" id="guardian_address" value="{{ old('guardian_address', $guardianAddress) }}">


                        {{-- 4. Enrollment Information --}}
                        <div class="col-12 mt-3 pt-2.5 border-top" id="enrollmentSectionCard">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="step-badge">4</span>
                                    <div>
                                        <h5 class="mb-0 section-heading">ការសិក្សា <span class="section-subheading">(Enrollment Information)</span></h5>
                                    </div>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 fw-semibold text-nowrap" id="active_shift_badge" style="background: #fef3c7; color: #78350f; border: 1px solid #fde68a; white-space: nowrap;">
                                    ទម្រង់ចុះឈ្មោះ៖ វេនព្រឹក
                                </span>
                            </div>
                            <p class="text-muted small mt-1 mb-0 ms-1">ជ្រើសរើសវេនសិក្សា (ពេលព្រឹក ឬ ពេលរសៀល) សម្រាប់ការចុះឈ្មោះចូលរៀន</p>
                        </div>

                    @php
                        $defaultMorningShift = $shifts->first(fn($s) => str_contains(mb_strtolower($s->name), 'morning') || str_contains($s->name, 'ព្រឹក'));
                        $defaultAfternoonShift = $shifts->first(fn($s) => str_contains(mb_strtolower($s->name), 'afternoon') || str_contains(mb_strtolower($s->name), 'day') || str_contains($s->name, 'រសៀល'));
                        $morningShiftId = $defaultMorningShift?->id ?? ($shifts->first()?->id ?? '');
                        $afternoonShiftId = $defaultAfternoonShift?->id ?? ($shifts->skip(1)->first()?->id ?? $morningShiftId);
                        $currentStudentShiftId = old('school_shift_id', $student->school_shift_id ?? $morningShiftId);
                    @endphp
                    <input type="hidden" name="section_id" id="section_id" value="{{ old('section_id', $student->section_id) }}">
                    <input type="hidden" name="student_status_id" id="student_status_id" value="{{ old('student_status_id', $student->student_status_id ?? 1) }}">

                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">វេនសិក្សា (Shift) <span class="text-danger">*</span></label>
                                <select name="school_shift_id" id="school_shift_id" class="form-select form-control-custom @error('school_shift_id') is-invalid @enderror" onchange="onShiftDropdownChange(this)" required>
                                    @foreach($shifts as $s)
                                        @php
                                            $sName = mb_strtolower($s->name);
                                            $isAft = (str_contains($sName, 'afternoon') || str_contains($sName, 'day') || str_contains($s->name, 'រសៀល'));
                                            $sType = $isAft ? 'afternoon' : 'morning';
                                        @endphp
                                        <option value="{{ $s->id }}" data-shift-type="{{ $sType }}" {{ $currentStudentShiftId == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }} ({{ $isAft ? 'រៀនវេនរសៀល' : 'រៀនវេនព្រឹក' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('school_shift_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">ឆ្នាំសិក្សា (Academic Year) <span class="text-danger">*</span></label>
                                <select name="academic_year_id" id="academic_year_id" class="form-select form-control-custom @error('academic_year_id') is-invalid @enderror" onchange="onAcademicYearChange()" required>
                                    <option value="">ជ្រើសរើសឆ្នាំសិក្សា</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id', $student->academic_year_id) == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }} {{ $year->is_active ? '(បច្ចុប្បន្ន)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">ថ្នាក់រៀន (Class) <span class="text-danger">*</span></label>
                                <select name="school_class_id" id="school_class_id" class="form-select form-control-custom @error('school_class_id') is-invalid @enderror" required>
                                    <option value="">ជ្រើសរើសថ្នាក់រៀន</option>
                                    @foreach($classes as $c)
                                        @php
                                            $cName = ($c->grade?->name ?? 'Class ' . $c->id) . ($c->section?->name ? ' — ' . $c->section->name : '');
                                            $isMorn = (str_contains(mb_strtolower($cName), 'ព្រឹក') || str_contains(strtolower($cName), 'morning'));
                                            $isAft = (str_contains(mb_strtolower($cName), 'រសៀល') || str_contains(strtolower($cName), 'afternoon'));
                                            $classShift = $isMorn ? 'morning' : ($isAft ? 'afternoon' : 'both');
                                        @endphp
                                        <option value="{{ $c->id }}"
                                                data-shift="{{ $classShift }}"
                                                data-year-id="{{ $c->academic_year_id }}"
                                                data-section-id="{{ $c->section_id }}"
                                                {{ old('school_class_id', $student->school_class_id) == $c->id ? 'selected' : '' }}>
                                            {{ $cName }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="d-flex justify-content-end mt-1">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="checkbox" id="show_all_classes_toggle" onchange="toggleAllClasses(this.checked)" style="cursor: pointer;">
                                        <label class="form-check-label text-muted" for="show_all_classes_toggle" style="font-size: 0.72rem; pointer;">បង្ហាញគ្រប់ថ្នាក់</label>
                                    </div>
                                </div>
                                @error('school_class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">ថ្ងៃចុះឈ្មោះ (Enroll Date) <span class="text-danger">*</span></label>
                                <input type="date" name="enrollment_date" class="form-control form-control-custom @error('enrollment_date') is-invalid @enderror" value="{{ old('enrollment_date', $student->enrollment_date ? \Carbon\Carbon::parse($student->enrollment_date)->format('Y-m-d') : '') }}" required>
                                @error('enrollment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">ទម្រង់សិក្សា (Study Mode) <span class="text-danger">*</span></label>
                                <select name="study_mode" id="study_mode" class="form-select form-control-custom @error('study_mode') is-invalid @enderror" required>
                                    <option value="full_time" {{ old('study_mode', $student->study_mode ?? 'full_time') === 'full_time' ? 'selected' : '' }}>
                                        ពេញម៉ោង (Full-Time)
                                    </option>
                                    <option value="part_time" {{ old('study_mode', $student->study_mode) === 'part_time' ? 'selected' : '' }}>
                                        ក្រៅម៉ោង (Part-Time)
                                    </option>
                                    <option value="dual" {{ old('study_mode', $student->study_mode) === 'dual' ? 'selected' : '' }}>
                                        ទាំងពេញម៉ោង និង ក្រៅម៉ោង (Full-Time & Part-Time)
                                    </option>
                                </select>
                                @error('study_mode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">ប្រភេទសិស្ស (Category)</label>
                                <select name="student_category_id" class="form-select form-control-custom @error('student_category_id') is-invalid @enderror">
                                    <option value="">ជ្រើសរើសប្រភេទសិស្ស</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('student_category_id', $student->student_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->khmer_name ?? $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                        {{-- Actions --}}
                        <div class="col-12 d-flex align-items-center justify-content-end gap-2 pt-3 mt-3 border-top">
                            <a href="{{ route('students.index') }}" class="btn-cancel-custom" title="Cancel">
                                <i class="bi bi-x-lg"></i>
                                <span>បោះបង់</span>
                            </a>
                            <button type="submit" class="btn-save-custom" id="btnSubmitStudent" title="Update Student">
                                <i class="bi bi-save"></i>
                                <span>រក្សាទុក</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /col-12 --}}
    </div>{{-- /row --}}
</form>

@endsection

@push('styles')
<style>
.form-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 1.25rem 1.5rem;
    max-width: 100% !important;
    width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* ── Modern Step Badge & Section Headings ── */
.step-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    border-radius: 6px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.8rem;
    box-shadow: 0 2px 4px rgba(217, 119, 6, 0.22);
    margin-right: 0.5rem;
    flex-shrink: 0;
}
.section-heading {
    font-family: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif !important;
    color: #78350f !important;
    font-weight: 400 !important;
    font-size: 1.05rem !important;
    line-height: 1.35;
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
}
.section-subheading {
    font-family: var(--font-sans) !important;
    font-size: 0.88rem !important;
    color: #64748b !important;
    font-weight: 400 !important;
    margin-left: 0.35rem;
}

/* ── Compact Photo Uploader Card ── */
.photo-card-box {
    width: 112px;
    height: 150px;
    margin: 0 auto;
    cursor: pointer;
    position: relative;
    user-select: none;
    border-radius: 10px;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
}
.photo-card-box:hover {
    transform: translateY(-2px);
}
.photo-inner-wrapper {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    border: 2px dashed #cbd5e1;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
}
.photo-card-box:hover .photo-inner-wrapper {
    border-color: #f59e0b;
    border-style: solid;
    background: #fffdf5;
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.16);
}
.photo-img-tag {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
}
.photo-placeholder-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6px;
    text-align: center;
}
.photo-icon-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background-color: #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 4px;
    transition: all 0.2s ease;
}
.photo-card-box:hover .photo-icon-avatar {
    background-color: #fef3c7;
    color: #d97706;
    transform: scale(1.05);
}
.photo-hint-main {
    font-size: 0.7rem;
    font-weight: 600;
    color: #475569;
    line-height: 1.2;
}
.photo-hint-sub {
    font-size: 0.62rem;
    color: #94a3b8;
    margin-top: 2px;
}
.photo-hover-mask {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(2px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 500;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}
.photo-card-box:hover .photo-hover-mask {
    opacity: 1;
}
.photo-floating-badge {
    position: absolute;
    bottom: -4px;
    right: -4px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.68rem;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 5px rgba(217, 119, 6, 0.35);
    transition: transform 0.2s ease;
    z-index: 2;
}
.photo-card-box:hover .photo-floating-badge {
    transform: scale(1.1);
}
.btn-photo-action {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 500;
    color: #475569;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.btn-photo-action:hover {
    background: #f8fafc;
    border-color: #f59e0b;
    color: #d97706;
}
.btn-photo-remove {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    padding: 0;
    color: #ef4444;
    background: #fff;
    border: 1px solid #fca5a5;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.15s ease;
    font-size: 0.68rem;
}
.btn-photo-remove:hover {
    background: #fee2e2;
    border-color: #ef4444;
    color: #b91c1c;
}

/* ── Standard Form Controls (Unified to 0.95rem / 42px Standard for Khmer Typography) ── */
.form-label-custom {
    font-weight: 600;
    font-size: 0.95rem;
    color: #1e293b;
    margin-bottom: 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    min-height: 24px;
    line-height: 1.45;
}
.form-control-custom {
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 0.95rem;
    height: 42px;
    padding: 0.42rem 0.85rem;
    color: #0f172a;
    transition: all 0.15s ease-in-out;
    background-color: #ffffff;
    box-sizing: border-box;
}
select.form-select.form-control-custom {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 13px 8px !important;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 0.95rem;
    height: 42px;
    padding: 0.42rem 2.3rem 0.42rem 0.85rem !important;
    color: #0f172a;
    transition: all 0.15s ease-in-out;
    background-color: #ffffff;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    box-sizing: border-box;
}
.form-control-custom:focus, .form-select.form-control-custom:focus {
    border-color: #f59e0b !important;
    box-shadow: 0 0 0 2.5px rgba(245, 158, 11, 0.18) !important;
    background-color: #ffffff !important;
}

.btn-cancel-custom {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.42rem 1rem;
    font-size: 0.95rem;
    font-weight: 500;
    color: #475569;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s ease-in-out;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    height: 42px;
    box-sizing: border-box;
}
.btn-cancel-custom:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #1e293b;
}
.btn-cancel-custom i {
    font-size: 0.9rem;
    color: #64748b;
    line-height: 1;
}
.btn-save-custom {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.42rem 1.25rem;
    font-size: 0.95rem;
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
    border: 1px solid #d97706;
    border-radius: 6px;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    box-shadow: 0 2px 4px rgba(217, 119, 6, 0.25);
    height: 42px;
    box-sizing: border-box;
}
.btn-save-custom:hover {
    background: linear-gradient(180deg, #d97706 0%, #b45309 100%);
    border-color: #b45309;
    color: #ffffff;
    box-shadow: 0 3px 8px rgba(217, 119, 6, 0.35);
    transform: translateY(-1px);
}
.btn-save-custom:active {
    transform: translateY(0);
}
.btn-save-custom i {
    font-size: 0.85rem;
    line-height: 1;
}
</style>
@endpush

@push('scripts')
<script>
function previewImage(input) {
    const feedback = document.getElementById('duplicate_photo_feedback');
    if (feedback) {
        feedback.textContent = '';
        feedback.classList.add('d-none');
    }

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photo_preview');
            const placeholder = document.getElementById('photo_placeholder');
            const removeBtn = document.getElementById('btn_remove_photo');
            const removeHidden = document.getElementById('remove_photo_hidden');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
            if (removeBtn) {
                removeBtn.classList.remove('d-none');
            }
            if (removeHidden) {
                removeHidden.value = '0';
            }
        }
        reader.readAsDataURL(input.files[0]);

        // Live check if duplicate photo
        if (typeof checkDuplicateUrl !== 'undefined') {
            const fd = new FormData();
            fd.append('photo', input.files[0]);
            fd.append('exclude_id', '{{ $student->id }}');
            fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '');
            fetch(checkDuplicateUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data && data.exists && data.field === 'photo') {
                    if (feedback) {
                        feedback.textContent = data.message;
                        feedback.classList.remove('d-none');
                    }
                }
            })
            .catch(() => {});
        }
    } else {
        removePhoto();
    }
}

function removePhoto() {
    const input = document.getElementById('photo_input');
    if (input) input.value = '';
    const preview = document.getElementById('photo_preview');
    if (preview) {
        preview.src = '';
        preview.classList.add('d-none');
    }
    const placeholder = document.getElementById('photo_placeholder');
    if (placeholder) placeholder.classList.remove('d-none');
    const removeBtn = document.getElementById('btn_remove_photo');
    if (removeBtn) removeBtn.classList.add('d-none');
    const removeHidden = document.getElementById('remove_photo_hidden');
    if (removeHidden) removeHidden.value = '1';
    const feedback = document.getElementById('duplicate_photo_feedback');
    if (feedback) {
        feedback.textContent = '';
        feedback.classList.add('d-none');
    }
}

let currentEnrollmentShift = 'morning';
let allClassOptions = null;

function initClassCache() {
    if (allClassOptions !== null) return;
    const classSelect = document.getElementById('school_class_id');
    if (!classSelect) return;
    allClassOptions = [];
    for (let opt of classSelect.options) {
        if (!opt.value) continue;
        allClassOptions.push({
            value: opt.value,
            text: opt.textContent.trim(),
            shift: opt.dataset.shift || 'both',
            yearId: opt.dataset.yearId || '',
            sectionId: opt.dataset.sectionId || ''
        });
    }
}

function onAcademicYearChange() {
    const showAll = document.getElementById('show_all_classes_toggle')?.checked || false;
    filterClassesAndSections(currentEnrollmentShift, showAll);
}

function onShiftDropdownChange(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const shiftType = selectedOption ? (selectedOption.dataset.shiftType || 'morning') : 'morning';
    switchEnrollmentShift(shiftType, false);
}

function switchEnrollmentShift(shift, syncDropdown = true) {
    currentEnrollmentShift = shift;
    const badge = document.getElementById('active_shift_badge');
    const shiftSelect = document.getElementById('school_shift_id');
    const showAll = document.getElementById('show_all_classes_toggle')?.checked || false;

    if (shift === 'morning') {
        if (badge) {
            badge.style.background = '#fef3c7';
            badge.style.color = '#b45309';
            badge.style.borderColor = '#fde68a';
            badge.innerHTML = 'ទម្រង់ចុះឈ្មោះ៖ វេនព្រឹក';
        }

        if (syncDropdown && shiftSelect && shiftSelect.options) {
            for (let opt of shiftSelect.options) {
                if (opt.dataset.shiftType === 'morning') {
                    shiftSelect.value = opt.value;
                    break;
                }
            }
        }
    } else {
        if (badge) {
            badge.style.background = '#e0e7ff';
            badge.style.color = '#4338ca';
            badge.style.borderColor = '#c7d2fe';
            badge.innerHTML = 'ទម្រង់ចុះឈ្មោះ៖ វេនរសៀល';
        }

        if (syncDropdown && shiftSelect && shiftSelect.options) {
            for (let opt of shiftSelect.options) {
                if (opt.dataset.shiftType === 'afternoon') {
                    shiftSelect.value = opt.value;
                    break;
                }
            }
        }
    }

    filterClassesAndSections(shift, showAll);
}

const studentAssignedClassId = "{{ (string) old('school_class_id', $student->school_class_id) }}";

function toggleAllClasses(showAll) {
    filterClassesAndSections(currentEnrollmentShift, showAll);
}

function filterClassesAndSections(shift, showAll) {
    initClassCache();
    const classSelect = document.getElementById('school_class_id');
    const yearSelect = document.getElementById('academic_year_id');
    const sectionSelect = document.getElementById('section_id');
    const showAllCheckbox = document.getElementById('show_all_classes_toggle');
    if (!classSelect || !allClassOptions) return;

    const selectedYearId = yearSelect ? String(yearSelect.value).trim() : '';
    const currentClassValue = classSelect.value || studentAssignedClassId;

    classSelect.innerHTML = '';

    const placeholderOpt = document.createElement('option');
    placeholderOpt.value = '';

    if (!selectedYearId) {
        placeholderOpt.textContent = '-- សូមជ្រើសរើសឆ្នាំសិក្សាជាមុនសិន --';
        classSelect.appendChild(placeholderOpt);
        return;
    }

    placeholderOpt.textContent = 'ជ្រើសរើសថ្នាក់រៀន';
    classSelect.appendChild(placeholderOpt);

    // 1. Get all classes for the selected academic year
    const yearClasses = allClassOptions.filter(item => !item.yearId || String(item.yearId) === selectedYearId);

    // 2. Filter by shift or show all
    let targetClasses = [];
    if (showAll) {
        targetClasses = yearClasses;
    } else {
        targetClasses = yearClasses.filter(item => item.shift === shift || item.shift === 'both');
        // If no classes match this specific shift, fallback to all classes in this year so user is never blocked!
        if (targetClasses.length === 0 && yearClasses.length > 0) {
            targetClasses = yearClasses;
            if (showAllCheckbox) {
                showAllCheckbox.checked = true;
            }
        }
    }

    // 3. In edit mode, ensure student's previously assigned class is ALWAYS kept in the list
    if (studentAssignedClassId && !targetClasses.some(c => String(c.value) === String(studentAssignedClassId))) {
        const studentClassItem = allClassOptions.find(c => String(c.value) === String(studentAssignedClassId));
        if (studentClassItem) {
            targetClasses.unshift(studentClassItem);
            if (showAllCheckbox) {
                showAllCheckbox.checked = true;
            }
        }
    }

    let preserved = false;

    targetClasses.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.value;
        opt.textContent = item.text;
        opt.dataset.shift = item.shift;
        opt.dataset.yearId = item.yearId;
        opt.dataset.sectionId = item.sectionId;

        if (String(item.value) === String(currentClassValue)) {
            opt.selected = true;
            preserved = true;
        }
        classSelect.appendChild(opt);
    });

    if (targetClasses.length === 0) {
        placeholderOpt.textContent = '-- មិនមានថ្នាក់រៀនក្នុងឆ្នាំសិក្សានេះទេ --';
    }

    if (!preserved && currentClassValue && targetClasses.length > 0) {
        if (targetClasses.some(c => String(c.value) === String(currentClassValue))) {
            classSelect.value = currentClassValue;
        }
    }

    if (sectionSelect && sectionSelect.options) {
        for (let opt of sectionSelect.options) {
            if (!opt.value) continue;
            const optShift = opt.dataset.shift;
            if (showAll || optShift === shift || optShift === 'both') {
                opt.hidden = false;
                opt.disabled = false;
            } else {
                opt.hidden = true;
                opt.disabled = true;
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initClassCache();

    // Auto-fill Section when Class is selected
    const classSelect = document.getElementById('school_class_id');
    if (classSelect) {
        classSelect.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const secId = selectedOpt?.dataset?.sectionId;
            const secSelect = document.getElementById('section_id');
            if (secId && secSelect) {
                secSelect.value = secId;
            }
        });
    }

    let initShift = 'morning';
    const shiftInput = document.getElementById('school_shift_id');
    if (shiftInput && shiftInput.selectedIndex >= 0) {
        const selectedOpt = shiftInput.options[shiftInput.selectedIndex];
        initShift = selectedOpt?.dataset?.shiftType || 'morning';
    } else if (shiftInput && String(shiftInput.value) === String("{{ $afternoonShiftId }}")) {
        initShift = 'afternoon';
    } else if (classSelect && classSelect.selectedIndex >= 0) {
        const selectedClassOpt = classSelect.options[classSelect.selectedIndex];
        if (selectedClassOpt && selectedClassOpt.dataset.shift === 'afternoon') {
            initShift = 'afternoon';
        }
    }
    switchEnrollmentShift(initShift, false);

    // Prevent double submission
    const studentForm = document.getElementById('studentForm');
    if (studentForm) {
        studentForm.addEventListener('submit', function (e) {
            const btn = document.getElementById('btnSubmitStudent');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> <span>កំពុងរក្សាទុក...</span>';
                setTimeout(function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-save"></i> <span>រក្សាទុក</span>';
                }, 8000);
            }
        });
    }

    // ── Real-time Duplicate Checking (Debounced with Current Student Excluded) ──
    const checkDuplicateUrl = "{{ route('students.check-duplicate') }}";
    const csrfToken = "{{ csrf_token() }}";
    const currentStudentId = "{{ $student->id }}";
    let duplicateCheckTimer = null;

    function runDuplicateCheck(data, callback) {
        clearTimeout(duplicateCheckTimer);
        duplicateCheckTimer = setTimeout(() => {
            data.exclude_id = currentStudentId;
            fetch(checkDuplicateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => callback(result))
            .catch(err => console.error('Duplicate check error:', err));
        }, 400);
    }

    // 1. Email duplicate check
    const emailInput = document.getElementById('student_email');
    const emailFeedback = document.getElementById('duplicate_email_feedback');
    if (emailInput && emailFeedback) {
        emailInput.addEventListener('input', function() {
            const val = this.value.trim();
            if (!val) {
                emailFeedback.classList.add('d-none');
                this.classList.remove('is-invalid');
                return;
            }
            runDuplicateCheck({ email: val }, function(res) {
                if (res.exists && res.field === 'email') {
                    emailFeedback.textContent = res.message;
                    emailFeedback.classList.remove('d-none');
                    emailInput.classList.add('is-invalid');
                } else {
                    emailFeedback.classList.add('d-none');
                    emailInput.classList.remove('is-invalid');
                }
            });
        });
    }

    // 3. Name + DOB duplicate check
    const dobInput = document.getElementById('student_dob');
    const dobFeedback = document.getElementById('duplicate_dob_feedback');
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const firstNameKhInput = document.getElementById('first_name_khmer');
    const lastNameKhInput = document.getElementById('last_name_khmer');

    function checkNameAndDob() {
        if (!dobInput || !dobInput.value) return;
        const payload = {
            dob: dobInput.value,
            first_name: firstNameInput?.value?.trim() || '',
            last_name: lastNameInput?.value?.trim() || '',
            first_name_khmer: firstNameKhInput?.value?.trim() || '',
            last_name_khmer: lastNameKhInput?.value?.trim() || ''
        };
        if (!payload.first_name && !payload.first_name_khmer) return;

        runDuplicateCheck(payload, function(res) {
            if (res.exists && res.field === 'name_dob') {
                dobFeedback.textContent = res.message;
                dobFeedback.classList.remove('d-none');
                dobInput.classList.add('is-invalid');
            } else {
                dobFeedback.classList.add('d-none');
                dobInput.classList.remove('is-invalid');
            }
        });
    }

    if (dobInput) dobInput.addEventListener('change', checkNameAndDob);
    if (firstNameInput) firstNameInput.addEventListener('blur', checkNameAndDob);
    if (lastNameInput) lastNameInput.addEventListener('blur', checkNameAndDob);
    if (firstNameKhInput) firstNameKhInput.addEventListener('blur', checkNameAndDob);
    if (lastNameKhInput) lastNameKhInput.addEventListener('blur', checkNameAndDob);
});

function toggleOtherGuardianSection() {
    const isOther = document.getElementById('primary_other')?.checked;
    const otherFields = document.getElementById('otherGuardianFields');
    const generalEmail = document.getElementById('generalGuardianEmailCol');
    if (otherFields) otherFields.style.display = isOther ? 'block' : 'none';
    if (generalEmail) generalEmail.style.display = isOther ? 'none' : 'block';
}

function compileGuardianAddress() {
    const v = document.getElementById('guardian_village')?.value?.trim();
    const c = document.getElementById('guardian_commune')?.value?.trim();
    const d = document.getElementById('guardian_district')?.value?.trim();
    const p = document.getElementById('guardian_province')?.value?.trim();

    let parts = [];
    if (v) parts.push('ភូមិ ' + v);
    if (c) parts.push('ឃុំ/សង្កាត់ ' + c);
    if (d) parts.push('ស្រុក/ខណ្ឌ ' + d);
    if (p) parts.push(p);

    const hiddenInput = document.getElementById('guardian_address');
    if (hiddenInput) {
        hiddenInput.value = parts.join(', ');
    }
}

async function copyStudentAddressToGuardian() {
    const sVillage = document.getElementById('village')?.value || document.querySelector('[name="village"]')?.value || '';
    const sCommune = document.getElementById('commune')?.value || document.querySelector('[name="commune"]')?.value || '';
    const sDistrict = document.getElementById('district')?.value || document.querySelector('[name="district"]')?.value || '';
    const sProvince = document.getElementById('province')?.value || document.querySelector('select[name="province"]')?.value || '';

    const gProvince = document.getElementById('guardian_province');
    if (gProvince) {
        gProvince.value = sProvince;
        if (typeof handleProvinceChange === 'function') {
            await handleProvinceChange('guardian', sDistrict, sCommune, sVillage);
        }
    }

    compileGuardianAddress();
}
</script>
@include('students.partials.cambodia_districts_js')
@endpush
