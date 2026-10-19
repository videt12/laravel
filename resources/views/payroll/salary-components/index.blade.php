@extends('layouts.app')
@section('title', 'សមាសភាគប្រាក់បៀវត្ស — School Management System')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('salary-structures.index') }}" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">សមាសភាគ</li>
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
        <div class="banner-icon"><i class="bi bi-list-check"></i></div>
        <div>
            <h1>សមាសភាគប្រាក់បៀវត្ស</h1>
            <p>ប្រាក់ឧបត្ថម្ភ និងការកាត់ប្រាក់ដែលកំណត់ទៅលើរចនាសម្ព័ន្ធប្រាក់បៀវត្ស។</p>
        </div>
    </div>
    <div>
        <a href="{{ route('salary-components.create') }}" class="btn btn-light btn-sm fw-semibold d-flex align-items-center gap-1.5 shadow-sm px-3 py-1.5" style="border-radius:6px; font-size:0.82rem;">
            <i class="bi bi-plus-lg"></i>
            <span>បង្កើតសមាសភាគថ្មី</span>
        </a>
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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរក…" id="searchInput">
        </div>
        <select name="structure_id" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">រចនាសម្ព័ន្ធទាំងអស់</option>
            @foreach($structures as $s)
                <option value="{{ $s->id }}" @selected(request('structure_id') == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
        <select name="type" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">ប្រភេទទាំងអស់</option>
            <option value="earning" @selected(request('type') === 'earning')>ប្រាក់បន្ថែម / ឧបត្ថម្ភ</option>
            <option value="deduction" @selected(request('type') === 'deduction')>ការកាត់ប្រាក់</option>
        </select>
        @if(request()->hasAny(['search','structure_id','type']))
            <a href="{{ route('salary-components.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="height:35px; border-radius:6px; font-size:0.8rem;">
                <i class="bi bi-x-circle"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

<div class="table-card">
    <table class="table-custom table">
        <thead>
            <tr>
                <th>ឈ្មោះសមាសភាគ</th>
                <th>រចនាសម្ព័ន្ធ</th>
                <th>ប្រភេទ</th>
                <th>រូបមន្តគណនា</th>
                <th class="text-center">សកម្ម</th>
                <th class="text-center">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($components as $comp)
            <tr>
                <td>
                    <div class="fw-medium">{{ $comp->name }}</div>
                    @if($comp->code)<div class="small text-muted"><code>{{ $comp->code }}</code></div>@endif
                </td>
                <td>{{ $comp->salaryStructure?->name ?? '<span class="text-muted">—</span>' }}</td>
                <td>
                    @if($comp->type === 'earning')
                        <span class="badge bg-success-subtle text-success-emphasis">ប្រាក់បន្ថែម / ឧបត្ថម្ភ</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger-emphasis">ការកាត់ប្រាក់</span>
                    @endif
                </td>
                <td>
                    @if($comp->calculation_type === 'percentage')
                        {{ number_format($comp->percentage, 2) }}% នៃប្រាក់គោល
                    @else
                        ទឹកប្រាក់ថេរ៖ ${{ number_format($comp->amount, 2) }}
                    @endif
                </td>
                <td class="text-center">
                    @if($comp->is_active)<i class="bi bi-check-circle-fill text-success"></i>
                    @else<i class="bi bi-x-circle-fill text-muted"></i>@endif
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('salary-components.edit', $comp) }}" class="btn btn-sm btn-outline-secondary" title="កែសម្រួល"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('salary-components.destroy', $comp) }}" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបសមាសភាគនេះមែនទេ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="លុប"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-list-check fs-3 d-block mb-2 opacity-50"></i>
                    មិនទាន់មានសមាសភាគប្រាក់បៀវត្សនៅឡើយទេ។
                    <a href="{{ route('salary-components.create') }}" class="d-block mt-1 text-primary">បង្កើតសមាសភាគដំបូង →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($components->hasPages())
    <div class="mt-3 d-flex justify-content-center">{{ $components->withQueryString()->links() }}</div>
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
