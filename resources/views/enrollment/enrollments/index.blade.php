@extends('layouts.app')

@section('title', 'គ្រប់គ្រងការចុះឈ្មោះ — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការចុះឈ្មោះ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បញ្ជីចុះឈ្មោះ</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════════ */
.page-title {
    font-size: 1.4rem; font-weight: 800; color: var(--text-dark);
    margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; background: #fffbeb; color: #d97706;
    border-radius: var(--radius-md); font-size: 1.15rem;
}
.page-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #fff; border: 1px solid var(--border-soft); border-radius: 0px !important;
    padding: 0.5rem 0.85rem; display: flex; align-items: center; justify-content: space-between;
    gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.search-wrapper { position: relative; width: 240px; }
.search-wrapper i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem; }
.search-wrapper input {
    width: 100%; padding: 0.35rem 0.75rem 0.35rem 2rem; font-size: 0.875rem; min-height: 36px; height: 36px;
    border: 1px solid var(--border); border-radius: 0px !important; transition: all 0.2s; line-height: 1.4;
}
.search-wrapper input:focus { border-color: var(--gold); box-shadow: 0 0 0 2px var(--gold-subtle); outline: none; }
.filter-select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    font-size: 0.85rem !important;
    min-height: 36px !important;
    height: 36px !important;
    line-height: 1.4 !important;
    padding: 0.35rem 2rem 0.35rem 0.75rem !important;
    border: 1px solid var(--border) !important;
    border-radius: 0px !important;
    background-color: #fff !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 14px 10px !important;
    vertical-align: middle !important;
    cursor: pointer;
}
.filter-select::-ms-expand {
    display: none !important;
}
.filter-select:focus { border-color: var(--gold); box-shadow: 0 0 0 2px var(--gold-subtle); outline: none; }

/* ═══════════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: 0px !important;
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc; color: var(--text-muted); font-size: 0.8rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
    padding: 0.4rem 0.75rem; border-bottom: 1px solid var(--border-soft); white-space: nowrap;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fafbfc; }
.table-custom tbody td {
    padding: 0.35rem 0.75rem; font-size: 0.86rem; color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft); vertical-align: middle;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════════
   CUSTOM UI COMPONENTS
═══════════════════════════════════════════════════════════════════ */
.student-name { font-weight: 600; color: #1e293b; text-decoration: none; font-size: 0.88rem; line-height: 1.25; }
.student-name:hover { color: var(--gold); }
.student-id { font-size: 0.78rem; color: var(--text-muted); font-family: monospace; line-height: 1.1; }
.class-badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    background: #fef3c7; color: #b45309; border: 1px solid #fde68a;
    border-radius: 0px !important; padding: 0.15rem 0.5rem;
    font-weight: 700; font-size: 0.82rem; letter-spacing: 0.3px;
}
.year-badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    background: #f1f5f9; color: #475569; border-radius: 0px !important;
    padding: 0.12rem 0.45rem; font-size: 0.78rem; font-weight: 600;
}
.status-badge {
    display: inline-flex; align-items: center; padding: 0.15rem 0.55rem;
    border-radius: 0px !important; font-size: 0.78rem; font-weight: 600;
}
.status-active { background: #dcfce7; color: #166534; }
.status-inactive { background: #f1f5f9; color: #475569; }
.status-pending { background: #fef3c7; color: #92400e; }

/* Table Actions */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 0px !important;
    color: var(--text-muted); transition: all 0.2s; background: transparent; border: none;
    font-size: 0.85rem;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-person-vcard"></i></div>
            គ្រប់គ្រងការចុះឈ្មោះ
        </h1>
        <p class="page-subtitle">តាមដាន និងគ្រប់គ្រងការចុះឈ្មោះចូលរៀនរបស់សិស្សតាមឆ្នាំសិក្សា។</p>
    </div>
    <div>
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary-action shadow-sm" style="background:#d97706; border-color:#b45309;">
            <i class="bi bi-person-plus me-2"></i>ចុះឈ្មោះថ្មី
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-card alert-card-success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     FILTER BAR
═══════════════════════════════════════════════════════════════════ --}}
<div class="filter-bar mb-2">
    <form action="{{ route('enrollments.index') }}" method="GET" class="filter-inputs m-0 w-100">
        
        {{-- Search --}}
        <div class="search-wrapper flex-grow-1" style="max-width: 320px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះសិស្ស ឬអត្តលេខ...">
        </div>

        {{-- Filter by Shift --}}
        <select name="shift" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">គ្រប់វេនទាំងអស់</option>
            <option value="morning" {{ request('shift') == 'morning' ? 'selected' : '' }}>វេនព្រឹក</option>
            <option value="afternoon" {{ request('shift') == 'afternoon' ? 'selected' : '' }}>វេនរសៀល</option>
        </select>

        {{-- Filter by Year --}}
        <select name="academic_year_id" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                    {{ $year->name }}
                </option>
            @endforeach
        </select>

        {{-- Filter by Status --}}
        <select name="status" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>សកម្ម</option>
            <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>អសកម្ម</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>រង់ចាំ</option>
        </select>

        {{-- Active Filters Chip (if any) --}}
        @if(request()->anyFilled(['search', 'academic_year_id', 'status', 'shift']))
            <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-ghost ms-2 text-danger" style="font-size:0.75rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាតការស្វែងរក
            </a>
        @endif
    </form>
</div>

{{-- Quick Shift Switcher Tabs --}}
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="text-muted small fw-semibold me-1"><i class="bi bi-funnel me-1"></i>បែងចែកតាមវេន៖</span>
    <a href="{{ request()->fullUrlWithQuery(['shift' => null]) }}"
       class="btn btn-sm {{ !request('shift') ? 'btn-primary' : 'btn-light border' }} rounded-pill px-3 py-1" style="font-size:0.78rem">
        ទាំងអស់
    </a>
    <a href="{{ request()->fullUrlWithQuery(['shift' => 'morning']) }}"
       class="btn btn-sm {{ request('shift') == 'morning' ? 'btn-warning text-dark fw-bold' : 'btn-light border' }} rounded-pill px-3 py-1" style="font-size:0.78rem">
        វេនព្រឹក
    </a>
    <a href="{{ request()->fullUrlWithQuery(['shift' => 'afternoon']) }}"
       class="btn btn-sm {{ request('shift') == 'afternoon' ? 'btn-dark text-white fw-bold' : 'btn-light border' }} rounded-pill px-3 py-1" style="font-size:0.78rem; {{ request('shift') == 'afternoon' ? 'background:#4338ca; border-color:#4338ca;' : '' }}">
        វេនរសៀល
    </a>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     DATA TABLE
═══════════════════════════════════════════════════════════════════ --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>សិស្ស</th>
                    <th>ថ្នាក់រៀន</th>
                    <th>ឆ្នាំសិក្សា</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;font-size:0.85rem;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <a href="#" class="student-name d-block">
                                        {{ $enrollment->student->user->name ?? 'មិនស្គាល់ឈ្មោះសិស្ស' }}
                                    </a>
                                    <div class="student-id">{{ $enrollment->student->admission_number ?? 'មិនមាន' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($enrollment->enrollmentDetail && $enrollment->enrollmentDetail->class)
                                <div class="class-badge">
                                    {{ $enrollment->enrollmentDetail->class->grade->name ?? '' }} — {{ $enrollment->enrollmentDetail->class->section->name ?? '' }}
                                </div>
                            @else
                                <span class="text-muted fst-italic">មិនទាន់កំណត់ថ្នាក់</span>
                            @endif
                        </td>
                        <td>
                            <span class="year-badge">
                                <i class="bi bi-calendar3"></i>
                                {{ $enrollment->academic_year->name ?? '—' }}
                            </span>
                        </td>
                        <td class="text-muted" style="font-size:0.78rem">
                            {{ $enrollment->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('M d, Y') : '—' }}
                        </td>
                        <td>
                            @php
                                $statusClass = 'status-inactive';
                                $statusLabel = $enrollment->status ?? 'មិនស្គាល់';
                                if($enrollment->status == 'Active') {
                                    $statusClass = 'status-active';
                                    $statusLabel = 'សកម្ម';
                                } elseif($enrollment->status == 'Pending') {
                                    $statusClass = 'status-pending';
                                    $statusLabel = 'រង់ចាំ';
                                } elseif($enrollment->status == 'Inactive') {
                                    $statusClass = 'status-inactive';
                                    $statusLabel = 'អសកម្ម';
                                } elseif($enrollment->status == 'Transferred') {
                                    $statusClass = 'status-inactive';
                                    $statusLabel = 'ផ្ទេរចេញ/ចូល';
                                } elseif($enrollment->status == 'Promoted') {
                                    $statusClass = 'status-active';
                                    $statusLabel = 'ឡើងថ្នាក់';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('enrollments.show', $enrollment) }}" class="action-btn" data-bs-toggle="tooltip" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('enrollments.edit', $enrollment) }}" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួល">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="action-btn action-btn-danger" 
                                        data-bs-toggle="tooltip" title="លុប"
                                        onclick="confirmDelete({{ $enrollment->id }}, '{{ addslashes($enrollment->student->user->name ?? 'Unknown') }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-journal-x fs-1"></i></div>
                            <h6 class="fw-bold text-dark">រកមិនឃើញទិន្នន័យចុះឈ្មោះទេ</h6>
                            <p class="text-muted small mb-0">សូមព្យាយាមផ្លាស់ប្តូរការច្រោះស្វែងរក ឬចុះឈ្មោះសិស្សថ្មី។</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($enrollments->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $enrollments->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:56px;height:56px">
                        <i class="bi bi-trash text-danger fs-3"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1">លុបកំណត់ត្រាចុះឈ្មោះ?</h6>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបកំណត់ត្រាចុះឈ្មោះរបស់សិស្ស <strong id="deleteStudentName"></strong> នេះមែនទេ?
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">យល់ព្រមលុប</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    function confirmDelete(id, name) {
        document.getElementById('deleteStudentName').textContent = name;
        document.getElementById('deleteForm').action = `/enrollments/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endsection
