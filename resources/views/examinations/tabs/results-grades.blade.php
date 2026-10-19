{{-- Tab 4: Results & Grades Parent Partial --}}
<div class="subtabs-wrapper">
    <div class="subtabs-nav">
        <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'results']) }}"
           class="subtab-link {{ $subtab === 'results' ? 'active' : '' }}">
            <i class="bi bi-pencil-square"></i>
            <span>លទ្ធផលប្រឡង</span>
            <span class="subtab-badge">{{ $counts['results'] ?? 0 }}</span>
        </a>
        <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'grades']) }}"
           class="subtab-link {{ $subtab === 'grades' ? 'active' : '' }}">
            <i class="bi bi-card-checklist"></i>
            <span>និទ្ទេសសិស្ស</span>
            <span class="subtab-badge">{{ $counts['grades'] ?? 0 }}</span>
        </a>
    </div>
</div>

@if($subtab === 'grades')
    @include('examinations.tabs.results.student-grades')
@else
    @include('examinations.tabs.results.exam-results')
@endif
