@extends('layouts.app')
@section('title', '{{ $salary_structure->name }} — រចនាសម្ព័ន្ធប្រាក់បៀវត្ស')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('salary-structures.index') }}" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">{{ $salary_structure->name }}</li>
    </ol>
</nav>
@endsection
@push('styles')
<style>
.header-card { 
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%); 
    border-radius: var(--radius-md, 8px); 
    padding: 0.85rem 1.25rem; 
    margin-bottom: 0.65rem; 
    color: #fff; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    gap: 0.75rem; 
    flex-wrap: wrap; 
    box-shadow: 0 2px 8px rgba(232, 168, 56, 0.15);
}
.header-card h1 { font-size: 1.15rem; font-weight: 700; margin: 0 0 .15rem; line-height: 1.2; }
.header-card p { font-size: .8rem; opacity: .9; margin: 0; }
.info-card { 
    background: #fff; 
    border: 1px solid var(--border-soft, #e5e7eb); 
    border-radius: var(--radius-md, 8px); 
    padding: 0.85rem 1rem; 
    box-shadow: 0 1px 3px rgba(0,0,0,.04); 
    margin-bottom: 0.65rem;
}
.section-title { font-size: .82rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: var(--text-muted, #64748b); margin-bottom: .65rem; padding-bottom: .35rem; border-bottom: 1px solid #f1f5f9; }
</style>
@endpush
@section('content')
<div class="header-card">
    <div>
        <h1>{{ $salary_structure->name }}</h1>
        <p>
            កូដ៖ <strong>{{ $salary_structure->code }}</strong>
            @if($salary_structure->employee_department) &bull; ផ្នែក៖ <strong>{{ $salary_structure->employee_department->name }}</strong> @endif
            @if($salary_structure->employee_position) &bull; តួនាទី៖ <strong>{{ $salary_structure->employee_position->name }}</strong> @endif
            @if($salary_structure->description) &bull; {{ $salary_structure->description }} @endif
        </p>
    </div>
    <div class="d-flex gap-1.5 flex-wrap">
        <a href="{{ route('salary-components.create', ['structure_id' => $salary_structure->id]) }}" class="btn btn-light btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm px-2.5 py-1.5" style="border-radius:6px; font-size:0.82rem;">
            <i class="bi bi-plus-lg"></i><span>បន្ថែមសមាសភាគ</span>
        </a>
        <a href="{{ route('salary-structures.edit', $salary_structure) }}" class="btn btn-light btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm px-2.5 py-1.5" style="border-radius:6px; font-size:0.82rem;">
            <i class="bi bi-pencil"></i><span>កែសម្រួល</span>
        </a>
        <a href="{{ route('salary-structures.index') }}" class="btn btn-dark btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm px-2.5 py-1.5" style="border-radius:6px; font-size:0.82rem; background:rgba(0,0,0,0.25); border:none;">
            <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-2 py-2 px-3 small">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-2.5">
    <div class="col-lg-4">
        <div class="info-card">
            <div class="section-title">ព័ត៌មានសង្ខេប</div>
            <div class="d-flex justify-content-between py-1.5 border-bottom small"><span class="text-muted">ផ្នែក (Department)</span><span class="fw-semibold text-dark">{{ $salary_structure->employee_department?->name ?? 'ទូទៅ / ទាំងអស់' }}</span></div>
            <div class="d-flex justify-content-between py-1.5 border-bottom small"><span class="text-muted">តួនាទី (Position)</span><span class="fw-semibold text-dark">{{ $salary_structure->employee_position?->name ?? 'ទូទៅ / ទាំងអស់' }}</span></div>
            <div class="d-flex justify-content-between py-1.5 border-bottom small"><span class="text-muted">ប្រាក់បៀវត្សគោល</span><strong class="text-dark">${{ number_format($salary_structure->base_salary, 2) }}</strong></div>
            <div class="d-flex justify-content-between py-1.5 border-bottom small"><span class="text-muted">ស្ថានភាព</span>
                @if($salary_structure->is_active)<span class="badge bg-success-subtle text-success-emphasis">សកម្ម</span>
                @else<span class="badge bg-secondary-subtle text-secondary-emphasis">អសកម្ម</span>@endif
            </div>
            <div class="d-flex justify-content-between py-1.5 border-bottom small"><span class="text-muted">ចំនួនសមាសភាគ</span><strong>{{ $salary_structure->salaryComponents->count() }}</strong></div>
            <div class="d-flex justify-content-between py-1.5 small"><span class="text-muted">កាលបរិច្ឆេទបង្កើត</span><span class="text-muted">{{ $salary_structure->created_at->format('d M Y') }}</span></div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="info-card">
            <div class="section-title d-flex align-items-center justify-content-between">
                <span>សមាសភាគប្រាក់បៀវត្ស</span>
                <a href="{{ route('salary-components.create', ['structure_id' => $salary_structure->id]) }}" class="btn btn-sm btn-outline-warning text-dark py-0.5 px-2" style="font-size:0.75rem; border-color:#e8a838;">
                    <i class="bi bi-plus-lg me-1"></i>បន្ថែមសមាសភាគ
                </a>
            </div>
            @if($salary_structure->salaryComponents->isEmpty())
                <div class="text-center py-4 text-muted small">
                    <i class="bi bi-list-check fs-3 d-block mb-1 opacity-50"></i>
                    មិនទាន់មានសមាសភាគណាមួយត្រូវបានកំណត់នៅឡើយទេ។
                    <a href="{{ route('salary-components.create', ['structure_id' => $salary_structure->id]) }}" class="d-block mt-1 text-primary">បន្ថែមសមាសភាគដំបូង →</a>
                </div>
            @else
                <div class="mb-2.5">
                    <div class="fw-semibold mb-1.5 small" style="color:#065f46;">ប្រាក់បន្ថែម / ឧបត្ថម្ភ</div>
                    @forelse($salary_structure->salaryComponents->where('type','earning') as $comp)
                    <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom small">
                        <div>
                            <span class="fw-medium">{{ $comp->name }}</span>
                            @if(!$comp->is_active)<span class="badge bg-secondary-subtle text-secondary-emphasis ms-1" style="font-size:.7rem;">អសកម្ម</span>@endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" style="font-size:.8rem;">
                                @if($comp->calculation_type === 'percentage')
                                    {{ number_format($comp->percentage, 2) }}% នៃប្រាក់គោល
                                @else
                                    ថេរ៖ ${{ number_format($comp->amount, 2) }}
                                @endif
                            </span>
                            <a href="{{ route('salary-components.edit', $comp) }}" class="btn btn-xs btn-outline-secondary btn-sm py-0 px-1" title="កែសម្រួល"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('salary-components.destroy', $comp) }}" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបសមាសភាគនេះមែនទេ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger btn-sm py-0 px-1" title="លុប"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-muted small py-1 fst-italic">គ្មានប្រាក់ឧបត្ថម្ភ</div>
                    @endforelse
                </div>
                <div>
                    <div class="fw-semibold mb-1.5 small" style="color:#991b1b;">ការកាត់ប្រាក់</div>
                    @forelse($salary_structure->salaryComponents->where('type','deduction') as $comp)
                    <div class="d-flex align-items-center justify-content-between py-1.5 border-bottom small">
                        <div>
                            <span class="fw-medium">{{ $comp->name }}</span>
                            @if(!$comp->is_active)<span class="badge bg-secondary-subtle text-secondary-emphasis ms-1" style="font-size:.7rem;">អសកម្ម</span>@endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" style="font-size:.8rem;">
                                @if($comp->calculation_type === 'percentage')
                                    {{ number_format($comp->percentage, 2) }}% នៃប្រាក់គោល
                                @else
                                    ថេរ៖ ${{ number_format($comp->amount, 2) }}
                                @endif
                            </span>
                            <a href="{{ route('salary-components.edit', $comp) }}" class="btn btn-xs btn-outline-secondary btn-sm py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('salary-components.destroy', $comp) }}" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបសមាសភាគនេះមែនទេ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger btn-sm py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-muted small py-1 fst-italic">គ្មានការកាត់ប្រាក់</div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
