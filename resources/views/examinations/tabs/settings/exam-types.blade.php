{{-- Secondary Tab: Exam Types --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 80px;">លេខសម្គាល់</th>
                    <th>ឈ្មោះ</th>
                    <th>ការពិពណ៌នា</th>
                    <th>ការប្រឡងដែលភ្ជាប់</th>
                    <th class="text-end pe-4" style="width: 140px;">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $khmerExamTypes = [
                        'Unit Test'           => 'តេស្តប្រចាំមេរៀន',
                        'Mid-Term Exam'       => 'ការប្រឡងពាក់កណ្តាលឆមាស',
                        'Final Exam'          => 'ការប្រឡងឆមាស',
                        'Class Test'          => 'តេស្តក្នុងថ្នាក់',
                        'Practical Exam'      => 'ការប្រឡងអនុវត្ត',
                        'Oral Exam'           => 'ការប្រឡងផ្ទាល់មាត់',
                        'Assignment Based'    => 'ការវាយតម្លៃតាមកិច្ចការ',
                        'Annual Exam'         => 'ការប្រឡងប្រចាំឆ្នាំ',
                        'Pre-Board Exam'      => 'ការប្រឡងសាកល្បង',
                    ];
                    $khmerDescriptions = [
                        'Short assessment covering a single unit of study' => 'ការវាយតម្លៃខ្លីៗលើមេរៀននីមួយៗ',
                        'Examination held at the midpoint of a term' => 'ការប្រឡងនៅពាក់កណ្តាលឆមាសសិក្សា',
                        'Comprehensive exam at the end of the academic term' => 'ការប្រឡងរួមចុងឆមាសសិក្សា',
                        'In-class assessment conducted by the teacher' => 'ការវាយតម្លៃក្នុងថ្នាក់ដឹកនាំដោយគ្រូបង្រៀន',
                        'Hands-on assessment of practical skills' => 'ការវាយតម្លៃជាក់ស្តែងលើជំនាញអនុវត្ត',
                        'Verbal assessment conducted by an examiner' => 'ការវាយតម្លៃផ្ទាល់មាត់ដោយគណៈមេប្រយោគ',
                        'Grade derived from submitted assignments' => 'ពិន្ទុដែលទទួលបានពីកិច្ចការដែលបានដាក់',
                        'Year-end comprehensive examination' => 'ការប្រឡងរួមបញ្ចប់ឆ្នាំសិក្សា',
                        'Practice exam before the final board examination' => 'ការប្រឡងសាកល្បងមុនការប្រឡងផ្លូវការ',
                    ];
                @endphp
                @forelse($examTypes_paginated as $type)
                    @php
                        $displayName = $khmerExamTypes[$type->name] ?? $type->name;
                        $displayDesc = $khmerDescriptions[$type->description] ?? ($type->description ?: '');
                    @endphp
                    <tr>
                        <td class="ps-4 text-muted small">#{{ $type->id }}</td>
                        <td class="fw-semibold text-dark">{{ $displayName }}</td>
                        <td class="text-muted">{{ $displayDesc ?: '—' }}</td>
                        <td>
                            <a href="{{ route('examinations.index', ['tab' => 'exams', 'exam_type_id' => $type->id]) }}" class="text-decoration-none">
                                <span class="badge bg-light text-dark border">
                                    {{ $type->exams_count }} ការប្រឡង
                                </span>
                            </a>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="action-btn" title="កែសម្រួលប្រភេទប្រឡង"
                                        data-id="{{ $type->id }}"
                                        data-name="{{ $displayName }}"
                                        data-description="{{ $displayDesc }}"
                                        onclick="openEditExamTypeModal(this)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($type->exams_count === 0)
                                    <button type="button" class="action-btn action-btn-danger" title="លុបប្រភេទប្រឡង"
                                            onclick="confirmDelete('{{ route('exam-types.destroy', $type->id) }}', '{{ addslashes($type->name) }}', 'Exam Type')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-2 d-block mb-2 text-muted opacity-50"></i>
                            មិនទាន់មានប្រភេទប្រឡងនៅឡើយទេ។ ចុច "+ បន្ថែមប្រភេទប្រឡង" ខាងលើដើម្បីបង្កើត។
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($examTypes_paginated && $examTypes_paginated->hasPages())
        <div class="p-3 border-top">
            {{ $examTypes_paginated->links() }}
        </div>
    @endif
</div>
