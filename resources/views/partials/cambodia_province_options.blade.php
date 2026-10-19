@php
    $selectedVal = trim((string) ($selected ?? ''));
    $provincesList = [
        ['code' => '12', 'km' => 'ភ្នំពេញ', 'en' => 'Phnom Penh', 'label' => 'រាជធានីភ្នំពេញ'],
        ['code' => '01', 'km' => 'បន្ទាយមានជ័យ', 'en' => 'Banteay Meanchey', 'label' => 'ខេត្តបន្ទាយមានជ័យ'],
        ['code' => '02', 'km' => 'បាត់ដំបង', 'en' => 'Battambang', 'label' => 'ខេត្តបាត់ដំបង'],
        ['code' => '03', 'km' => 'កំពង់ចាម', 'en' => 'Kampong Cham', 'label' => 'ខេត្តកំពង់ចាម'],
        ['code' => '04', 'km' => 'កំពង់ឆ្នាំង', 'en' => 'Kampong Chhnang', 'label' => 'ខេត្តកំពង់ឆ្នាំង'],
        ['code' => '05', 'km' => 'កំពង់ស្ពឺ', 'en' => 'Kampong Speu', 'label' => 'ខេត្តកំពង់ស្ពឺ'],
        ['code' => '06', 'km' => 'កំពង់ធំ', 'en' => 'Kampong Thom', 'label' => 'ខេត្តកំពង់ធំ'],
        ['code' => '07', 'km' => 'កំពត', 'en' => 'Kampot', 'label' => 'ខេត្តកំពត'],
        ['code' => '08', 'km' => 'កណ្តាល', 'en' => 'Kandal', 'label' => 'ខេត្តកណ្តាល'],
        ['code' => '23', 'km' => 'កែប', 'en' => 'Kep', 'label' => 'ខេត្តកែប'],
        ['code' => '09', 'km' => 'កោះកុង', 'en' => 'Koh Kong', 'label' => 'ខេត្តកោះកុង'],
        ['code' => '10', 'km' => 'ក្រចេះ', 'en' => 'Kratie', 'label' => 'ខេត្តក្រចេះ'],
        ['code' => '11', 'km' => 'មណ្ឌលគិរី', 'en' => 'Mondulkiri', 'label' => 'ខេត្តមណ្ឌលគិរី'],
        ['code' => '22', 'km' => 'ឧត្តរមានជ័យ', 'en' => 'Oddar Meanchey', 'label' => 'ខេត្តឧត្តរមានជ័យ'],
        ['code' => '24', 'km' => 'ប៉ៃលិន', 'en' => 'Pailin', 'label' => 'ខេត្តប៉ៃលិន'],
        ['code' => '13', 'km' => 'ព្រះវិហារ', 'en' => 'Preah Vihear', 'label' => 'ខេត្តព្រះវិហារ'],
        ['code' => '18', 'km' => 'ព្រះសីហនុ', 'en' => 'Preah Sihanouk', 'label' => 'ខេត្តព្រះសីហនុ'],
        ['code' => '14', 'km' => 'ព្រៃវែង', 'en' => 'Prey Veng', 'label' => 'ខេត្តព្រៃវែង'],
        ['code' => '15', 'km' => 'ពោធិ៍សាត់', 'en' => 'Pursat', 'label' => 'ខេត្តពោធិ៍សាត់'],
        ['code' => '16', 'km' => 'រតនគិរី', 'en' => 'Ratanakiri', 'label' => 'ខេត្តរតនគិរី'],
        ['code' => '17', 'km' => 'សៀមរាប', 'en' => 'Siem Reap', 'label' => 'ខេត្តសៀមរាប'],
        ['code' => '19', 'km' => 'ស្ទឹងត្រែង', 'en' => 'Stung Treng', 'label' => 'ខេត្តស្ទឹងត្រែង'],
        ['code' => '20', 'km' => 'ស្វាយរៀង', 'en' => 'Svay Rieng', 'label' => 'ខេត្តស្វាយរៀង'],
        ['code' => '21', 'km' => 'តាកែវ', 'en' => 'Takeo', 'label' => 'ខេត្តតាកែវ'],
        ['code' => '25', 'km' => 'ត្បូងឃ្មុំ', 'en' => 'Tboung Khmum', 'label' => 'ខេត្តត្បូងឃ្មុំ'],
    ];
@endphp
<option value="">ជ្រើសរើសខេត្ត</option>
@foreach($provincesList as $p)
    @php
        $isSelected = false;
        if ($selectedVal !== '') {
            $cleanSel = mb_strtolower(trim((string) preg_replace('/^(ខេត្ត|រាជធានី)\s*/u', '', $selectedVal)));
            $cleanKm = mb_strtolower(trim($p['km']));
            $cleanEn = mb_strtolower(trim($p['en']));
            $isSelected = ($selectedVal === $p['km'] || $selectedVal === $p['en'] || $selectedVal === $p['code'] || $cleanSel === $cleanKm || $cleanSel === $cleanEn);
        }
    @endphp
    <option value="{{ $p['km'] }}" data-code="{{ $p['code'] }}" data-en="{{ $p['en'] }}" {{ $isSelected ? 'selected' : '' }}>
        {{ $p['label'] }}
    </option>
@endforeach
