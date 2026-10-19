{{-- TAB 3: SCHOOL PROFILE --}}
<div class="tab-pane fade {{ $activeTab === 'school-profile' ? 'show active' : '' }}" id="tab-school-profile" role="tabpanel">

    <form id="schoolProfileForm" action="{{ route('settings.school-profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- Left column: School Logo & Basic Status --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-light border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-image me-2 text-warning"></i>ផ្លាកសញ្ញាសាលា
                        </h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="school-logo-preview mb-3 mx-auto">
                            @php
                                $displayLogoRel = ($school->logo_path && file_exists(public_path($school->logo_path)))
                                    ? $school->logo_path
                                    : (file_exists(public_path('images/school_logo.png')) ? 'images/school_logo.png' : null);
                                $displayLogo = $displayLogoRel ? (asset($displayLogoRel).'?v='.(file_exists(public_path($displayLogoRel)) ? filemtime(public_path($displayLogoRel)) : time())) : null;
                            @endphp
                            @if($displayLogo)
                                <img id="logoPreview" src="{{ $displayLogo }}" alt="School Logo" class="img-fluid rounded border p-2" style="max-height: 140px; max-width: 100%;">
                            @else
                                <div id="logoPlaceholder" class="rounded border p-4 bg-light text-muted d-flex flex-column align-items-center justify-content-center" style="height: 140px;">
                                    <i class="bi bi-building fs-1 text-warning mb-2"></i>
                                    <span class="small">មិនទាន់មាន Logo</span>
                                </div>
                                <img id="logoPreview" src="" alt="School Logo" class="img-fluid rounded border p-2 d-none" style="max-height: 140px; max-width: 100%;">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ជ្រើសរើសរូបភាព Logo ថ្មី</label>
                            <input type="file" name="logo" id="schoolLogoInput" class="form-control form-control-sm" accept="image/*">
                            <div class="form-text small text-muted">ប្រភេទឯកសារ៖ JPG, PNG, WEBP, SVG (ទំហំអតិបរមា 10MB)</div>
                        </div>

                        <hr class="my-3">

                        <div class="text-start">
                            <label class="form-label fw-semibold small text-dark">ស្ថានភាពប្រតិបត្តិការ</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="active" {{ old('status', $school->status) === 'active' ? 'selected' : '' }}>ដំណើរការ</option>
                                <option value="inactive" {{ old('status', $school->status) === 'inactive' ? 'selected' : '' }}>ផ្អាកដំណើរការ</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column: School Detailed Information --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-light border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-bank me-2 text-warning"></i>ព័ត៌មានទូទៅរបស់សាលា
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">ឈ្មោះសាលា <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $school->name) }}" required placeholder="ឈ្មោះសាលាផ្លូវការ">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">លេខទូរស័ព្ទ</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone) }}" placeholder="+855 23 000 000">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">អ៊ីមែល</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $school->email) }}" placeholder="info@school.edu.kh">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">គេហទំព័រ</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="bi bi-globe"></i></span>
                                    <input type="text" name="website" class="form-control" value="{{ old('website', $school->website) }}" placeholder="https://school.edu.kh">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">អាសយដ្ឋាន</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="អាសយដ្ឋានទីតាំងរបស់សាលា...">{{ old('address', $school->address) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i>រក្សាទុកព័ត៌មានសាលា
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
