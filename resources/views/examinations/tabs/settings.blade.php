{{-- Tab 5: Settings Parent Partial --}}
<div class="subtabs-wrapper">
    <div class="subtabs-nav">
        <a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales']) }}"
           class="subtab-link {{ $subtab === 'grade-scales' ? 'active' : '' }}">
            <i class="bi bi-bar-chart-steps"></i>
            <span>កម្រិតនិទ្ទេស</span>
            <span class="subtab-badge">{{ $counts['grade-scales'] ?? 0 }}</span>
        </a>
        <a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types']) }}"
           class="subtab-link {{ $subtab === 'exam-types' ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            <span>ប្រភេទប្រឡង</span>
            <span class="subtab-badge">{{ $counts['exam-types'] ?? 0 }}</span>
        </a>
    </div>
</div>

@if($subtab === 'exam-types')
    @include('examinations.tabs.settings.exam-types')
@else
    @include('examinations.tabs.settings.grade-scales')
@endif
