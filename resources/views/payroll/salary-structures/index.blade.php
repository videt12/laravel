@extends('layouts.app')
@section('title', 'រចនាសម្ព័ន្ធប្រាក់បៀវត្ស — School Management System')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</li>
    </ol>
</nav>
@endsection
@push('styles')
<style>
.page-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-md, 8px);
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.65rem;
    position: relative;
    overflow: hidden;
    color: #fff;
    box-shadow: 0 2px 8px rgba(232, 168, 56, 0.15);
}
.page-banner::before {
    content: '';
    position: absolute; top: -30px; right: -30px;
    width: 130px; height: 130px;
    background: rgba(255,255,255,.12); border-radius: 50%;
    pointer-events: none;
}
.page-banner::after {
    content: '';
    position: absolute; bottom: -25px; right: 70px;
    width: 90px; height: 90px;
    background: rgba(255,255,255,.07); border-radius: 50%;
    pointer-events: none;
}
.page-banner .banner-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,.2);
    border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: #fff; margin-bottom: 0;
    flex-shrink: 0;
}
.page-banner h1 { font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0 0 .15rem; line-height: 1.2; }
.page-banner p { font-size: .78rem; color: rgba(255,255,255,.9); margin: 0; line-height: 1.3; }

.stat-card {
    background: #fff; border: 1px solid var(--border-soft, #e5e7eb);
    border-radius: var(--radius-md, 8px); padding: 0.55rem 0.75rem;
    display: flex; align-items: center; gap: 0.65rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.03);
}
.stat-icon {
    width: 34px; height: 34px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;
}
.stat-value { font-size: 1.15rem; font-weight: 700; line-height: 1.1; color: var(--text-dark, #0f172a); }
.stat-label { font-size: .72rem; color: var(--text-muted, #64748b); margin-top: .1rem; }

.filter-bar {
    background: #fff; border: 1px solid var(--border-soft, #e5e7eb);
    border-radius: var(--radius-md, 8px); padding: 0.45rem 0.75rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.02); flex-wrap: wrap;
    margin-bottom: 0.65rem;
}
.filter-inputs { display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap; width: 100%; }
.search-wrapper { position: relative; width: 220px; }
.search-wrapper i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted, #64748b); font-size: 0.82rem; }
.search-wrapper input {
    width: 100%; height: 35px; padding: 0.35rem 0.75rem 0.35rem 2.1rem; font-size: 0.82rem;
    border: 1px solid var(--border, #cbd5e1); border-radius: 6px; transition: all .15s ease-in-out;
}
.search-wrapper input:focus, .form-select-custom:focus {
    border-color: #e8a838; box-shadow: 0 0 0 3px rgba(232,168,56,.18); outline: none;
}
.form-select-custom {
    height: 35px; padding: 0.35rem 1.8rem 0.35rem 0.65rem; font-size: 0.82rem;
    border: 1px solid var(--border, #cbd5e1); border-radius: 6px;
    background-color: #fff; transition: all .15s ease-in-out;
}

.table-card {
    background: #fff; border: 1px solid var(--border-soft, #e5e7eb);
    border-radius: var(--radius-md, 8px); overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    margin-bottom: 0.65rem;
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc; padding: .55rem .75rem; font-size: .76rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: .03em;
    color: var(--text-muted, #64748b); border-bottom: 1px solid var(--border-soft, #e5e7eb); white-space: nowrap;
}
.table-custom tbody td { padding: .55rem .75rem; font-size: .82rem; vertical-align: middle; border-bottom: 1px solid var(--border-soft, #e5e7eb); }
.table-custom tbody tr:last-child td { border-bottom: none; }
.table-custom tbody tr:hover { background: #fdfdfd; }
</style>
@endpush

@section('content')
<div class="page-banner d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2.5">
        <div class="banner-icon"><i class="bi bi-layers"></i></div>
        <div>
            <h1>រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</h1>
            <p>កំណត់ប្រាក់បៀវត្សគោល និងក្រុមនៃប្រាក់ឧបត្ថម្ភ/ការកាត់ប្រាក់សម្រាប់បុគ្គលិក។</p>
        </div>
    </div>
    <div>
        <a href="{{ route('salary-structures.create') }}" class="btn btn-light btn-sm fw-semibold d-flex align-items-center gap-1.5 shadow-sm px-3 py-1.5" style="border-radius:6px; font-size:0.82rem;">
            <i class="bi bi-plus-lg"></i>
            <span>បង្កើតរចនាសម្ព័ន្ធថ្មី</span>
        </a>
    </div>
</div>

<div class="row g-2 mb-2">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#92400e;"><i class="bi bi-layers"></i></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">រចនាសម្ព័ន្ធសរុប</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check-circle"></i></div>
            <div><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">សកម្ម</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe;color:#0369a1;"><i class="bi bi-list-check"></i></div>
            <div><div class="stat-value">{{ $stats['total_components'] }}</div><div class="stat-label">សមាសភាគសកម្ម</div></div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-2 py-2 px-3 small">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="filter-bar">
    <form method="GET" class="filter-inputs" id="filterForm">
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរករចនាសម្ព័ន្ធ…" id="searchInput">
        </div>
        <select name="department_id" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">គ្រប់ផ្នែកទាំងអស់</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
            @endforeach
        </select>
        <select name="position_id" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">គ្រប់តួនាទីទាំងអស់</option>
            @foreach($positions as $pos)
                <option value="{{ $pos->id }}" @selected(request('position_id') == $pos->id)>{{ $pos->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">ទាំងអស់</option>
            <option value="active" @selected(request('status') === 'active')>សកម្ម</option>
            <option value="inactive" @selected(request('status') === 'inactive')>អសកម្ម</option>
        </select>
        @if(request()->hasAny(['search','department_id','position_id','status']))
            <a href="{{ route('salary-structures.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="height:35px; border-radius:6px; font-size:0.8rem;">
                <i class="bi bi-x-circle"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

<div class="table-card">
    <table class="table-custom table">
        <thead>
            <tr>
                <th>ឈ្មោះរចនាសម្ព័ន្ធ</th>
                <th>កូដ</th>
                <th>ផ្នែក & តួនាទី</th>
                <th class="text-end">ប្រាក់បៀវត្សគោល</th>
                <th class="text-center">សមាសភាគ</th>
                <th class="text-center">ស្ថានភាព</th>
                <th class="text-center">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($structures as $s)
            <tr>
                <td>
                    <a href="{{ route('salary-structures.show', $s) }}" class="fw-semibold text-decoration-none text-dark">{{ $s->name }}</a>
                    @if($s->description)<div class="small text-muted">{{ Str::limit($s->description, 60) }}</div>@endif
                </td>
                <td><code>{{ $s->code }}</code></td>
                <td>
                    <div class="small fw-semibold text-dark">{{ $s->employee_department?->name ?? 'ទូទៅ' }}</div>
                    <div class="small text-muted">{{ $s->employee_position?->name ?? 'គ្រប់តួនាទី' }}</div>
                </td>
                <td class="text-end fw-semibold">${{ number_format($s->base_salary, 2) }}</td>
                <td class="text-center">
                    <span class="badge bg-primary-subtle text-primary-emphasis">{{ $s->component_count }}</span>
                </td>
                <td class="text-center">
                    @if($s->is_active)
                        <span class="badge bg-success-subtle text-success-emphasis">សកម្ម</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary-emphasis">អសកម្ម</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('salary-structures.show', $s) }}" class="btn btn-sm btn-outline-primary" title="មើលលម្អិត"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('salary-structures.edit', $s) }}" class="btn btn-sm btn-outline-secondary" title="កែសម្រួល"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('salary-structures.destroy', $s) }}" onsubmit="return confirm('តើអ្នកពិតជាចង់លុប ឬបិទរចនាសម្ព័ន្ធនេះមែនទេ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="លុប"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="bi bi-layers fs-3 d-block mb-2 opacity-50"></i>
                    មិនទាន់មានរចនាសម្ព័ន្ធប្រាក់បៀវត្សនៅឡើយទេ។
                    <a href="{{ route('salary-structures.create') }}" class="d-block mt-1 text-primary">បង្កើតរចនាសម្ព័ន្ធដំបូង →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($structures->hasPages())
    <div class="mt-3 d-flex justify-content-center">{{ $structures->withQueryString()->links() }}</div>
@endif
@endsection
@push('scripts')
<script>
let t;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(t); t = setTimeout(() => document.getElementById('filterForm').submit(), 500);
});
</script>
@endpush
