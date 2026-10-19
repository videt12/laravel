{{-- Secondary Tab: Grade Scales --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 130px;">កម្រិតនិទ្ទេស</th>
                    <th>ឈ្មោះកម្រិត</th>
                    <th>ភាគរយអប្បបរមា</th>
                    <th>ភាគរយអតិបរមា</th>
                    <th>ពិន្ទុ GPA</th>
                    <th class="text-end pe-4">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $khmerGrades = [
                        'Outstanding'  => 'ល្អប្រសើរ',
                        'Excellent'    => 'ល្អណាស់',
                        'Very Good'    => 'ល្អបង្គួរ',
                        'Good'         => 'ល្អ',
                        'Satisfactory' => 'សមរម្យ',
                        'Average'      => 'មធ្យម',
                        'Pass'         => 'ជាប់',
                        'Fail'         => 'ធ្លាក់',
                    ];

                    $gradeBadges = [
                        'A+' => ['bg' => '#167a48', 'color' => '#ffffff'],
                        'A'  => ['bg' => '#1c8f53', 'color' => '#ffffff'],
                        'B+' => ['bg' => '#5ba860', 'color' => '#ffffff'],
                        'B'  => ['bg' => '#f6b825', 'color' => '#111827'],
                        'C+' => ['bg' => '#f07926', 'color' => '#ffffff'],
                        'C'  => ['bg' => '#ff7d25', 'color' => '#ffffff'],
                        'D'  => ['bg' => '#fa4e24', 'color' => '#ffffff'],
                        'F'  => ['bg' => '#dc2638', 'color' => '#ffffff'],
                    ];
                @endphp
                @forelse($gradeScales as $scale)
                    @php
                        $badgeStyle = $gradeBadges[$scale->grade] ?? ['bg' => '#64748b', 'color' => '#ffffff'];
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="badge rounded-pill fw-bold" style="background-color: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['color'] }}; min-width: 58px; padding: 6px 16px; font-size: 0.95rem; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.08);">
                                {{ $scale->grade }}
                            </span>
                        </td>
                        <td class="fw-semibold text-dark">{{ $khmerGrades[$scale->name] ?? $scale->name }}</td>
                        <td>{{ number_format($scale->min_percentage, 2) }}%</td>
                        <td>{{ number_format($scale->max_percentage, 2) }}%</td>
                        <td><strong>{{ number_format($scale->gpa_point ?? 0, 2) }}</strong></td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="action-btn" title="កែសម្រួលកម្រិតនិទ្ទេស"
                                        data-id="{{ $scale->id }}"
                                        data-name="{{ $scale->name }}"
                                        data-grade="{{ $scale->grade }}"
                                        data-min="{{ $scale->min_percentage }}"
                                        data-max="{{ $scale->max_percentage }}"
                                        data-gpa="{{ $scale->gpa_point ?? '0.00' }}"
                                        onclick="openEditGradeScaleModal(this)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="action-btn action-btn-danger" title="លុបកម្រិតនិទ្ទេស"
                                        onclick="confirmDelete('{{ route('grade-scales.destroy', $scale->id) }}', '{{ addslashes($scale->name) }} ({{ $scale->grade }})', 'Grade Scale')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-bar-chart-steps fs-2 d-block mb-2 text-muted opacity-50"></i>
                            មិនទាន់មានកម្រិតនិទ្ទេសនៅឡើយទេ។ ចុច "+ បន្ថែមកម្រិតនិទ្ទេស" ខាងលើដើម្បីបង្កើត។
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
