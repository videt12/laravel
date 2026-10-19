<script>
/**
 * Cambodia 4-Level Cascading Address System
 * Province (រាជធានី-ខេត្ត) ➔ District (ក្រុង-ស្រុក-ខណ្ឌ) ➔ Commune (ឃុំ-សង្កាត់) ➔ Village (ភូមិ)
 */
const CAMBODIA_API = {
    districts: '<?php echo e(route("locations.cambodia.districts", [], false)); ?>',
    communes: '<?php echo e(route("locations.cambodia.communes", [], false)); ?>',
    villages: '<?php echo e(route("locations.cambodia.villages", [], false)); ?>'
};

// Client-side cache for static fallbacks
const LOCATION_CACHE = {
    provinces: null,
    districts: null,
    districtsByProvince: null,
    communes: null,
    communesByDistrict: null,
    villagesByCommune: null
};

async function fetchLocationData(url, params = {}) {
    try {
        const query = new URLSearchParams(params).toString();
        const res = await fetch(`${url}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const data = await res.json();
            if (Array.isArray(data) && data.length > 0) {
                return data;
            }
        }
    } catch (e) {
        console.warn('API fetch failed, trying local fallback:', e);
    }

    // Static JSON fallback if controller/session had issues or returned empty
    return await fallbackFetchData(url, params);
}

async function fallbackFetchData(url, params = {}) {
    try {
        if (url.includes('districts')) {
            if (!LOCATION_CACHE.districtsByProvince) {
                const res = await fetch('/data/cambodia/districts_by_province.json');
                LOCATION_CACHE.districtsByProvince = await res.json();
            }
            if (!LOCATION_CACHE.provinces) {
                const res = await fetch('/data/cambodia/provinces.json');
                LOCATION_CACHE.provinces = await res.json();
            }
            
            let pCode = params.province_code || '';
            if (!pCode && params.province) {
                const pInput = (params.province || '').toLowerCase().replace(/^(ខេត្ត|រាជធានី)\s*/u, '').trim();
                const normP = pInput.replace(/[éèêë]/g, 'e');
                const prov = LOCATION_CACHE.provinces.find(p => 
                    p.code === params.province ||
                    p.nameEn.toLowerCase() === pInput ||
                    p.nameKm.toLowerCase() === pInput ||
                    p.nameEn.toLowerCase().replace(/[éèêë]/g, 'e') === normP
                );
                if (prov) pCode = prov.code;
            }
            
            if (pCode && LOCATION_CACHE.districtsByProvince[pCode]) {
                return LOCATION_CACHE.districtsByProvince[pCode].map(d => ({
                    code: d.code,
                    nameKm: d.nameKm,
                    nameEn: d.nameEn,
                    label: d.nameKm
                }));
            }
        } else if (url.includes('communes')) {
            if (!LOCATION_CACHE.communesByDistrict) {
                const res = await fetch('/data/cambodia/communes_by_district.json');
                LOCATION_CACHE.communesByDistrict = await res.json();
            }
            
            let dCode = params.district_code || '';
            if (!dCode && params.district) {
                if (!LOCATION_CACHE.districts) {
                    const res = await fetch('/data/cambodia/districts.json');
                    LOCATION_CACHE.districts = await res.json();
                }
                const dInput = (params.district || '').toLowerCase().replace(/^(ស្រុក|ខណ្ឌ|ក្រុង)\s*/u, '').trim();
                const dist = LOCATION_CACHE.districts.find(d =>
                    d.code === params.district ||
                    d.nameKm.toLowerCase().replace(/^(ស្រុក|ខណ្ឌ|ក្រុង)\s*/u, '').trim() === dInput ||
                    d.nameEn.toLowerCase() === dInput
                );
                if (dist) dCode = dist.code;
            }
            
            if (dCode && LOCATION_CACHE.communesByDistrict[dCode]) {
                return LOCATION_CACHE.communesByDistrict[dCode].map(c => ({
                    code: c.code,
                    nameKm: c.nameKm,
                    nameEn: c.nameEn,
                    label: 'ឃុំ/សង្កាត់ ' + c.nameKm
                }));
            }
        } else if (url.includes('villages')) {
            if (!LOCATION_CACHE.villagesByCommune) {
                const res = await fetch('/data/cambodia/villages_by_commune.json');
                LOCATION_CACHE.villagesByCommune = await res.json();
            }
            
            let cCode = params.commune_code || '';
            if (!cCode && params.commune) {
                if (!LOCATION_CACHE.communes) {
                    const res = await fetch('/data/cambodia/communes.json');
                    LOCATION_CACHE.communes = await res.json();
                }
                const cInput = (params.commune || '').toLowerCase().replace(/^(ឃុំ|សង្កាត់|ឃុំ\/សង្កាត់)\s*/u, '').trim();
                const comm = LOCATION_CACHE.communes.find(c =>
                    c.code === params.commune ||
                    c.nameKm.toLowerCase().replace(/^(ឃុំ|សង្កាត់|ឃុំ\/សង្កាត់)\s*/u, '').trim() === cInput ||
                    c.nameEn.toLowerCase() === cInput
                );
                if (comm) cCode = comm.code;
            }
            
            if (cCode && LOCATION_CACHE.villagesByCommune[cCode]) {
                return LOCATION_CACHE.villagesByCommune[cCode].map(v => ({
                    code: v.code,
                    nameKm: v.nameKm,
                    nameEn: v.nameEn,
                    label: 'ភូមិ ' + v.nameKm
                }));
            }
        }
    } catch (err) {
        console.error('Static fallback error:', err);
    }
    return [];
}

function setSelectLoading(selectEl, loadingText = 'កំពុងផ្ទុក...') {
    if (!selectEl) return;
    selectEl.innerHTML = `<option value="">${loadingText}</option>`;
    selectEl.disabled = true;
}

function setSelectEmpty(selectEl, placeholderText) {
    if (!selectEl) return;
    selectEl.innerHTML = `<option value="">${placeholderText}</option>`;
    selectEl.disabled = true;
}

function populateSelectOptions(selectEl, items, selectedValue = '', defaultPlaceholder = 'ជ្រើសរើស') {
    if (!selectEl) return;
    
    selectEl.innerHTML = '';
    const defOpt = document.createElement('option');
    defOpt.value = '';
    defOpt.textContent = defaultPlaceholder;
    selectEl.appendChild(defOpt);
    
    if (!items || items.length === 0) {
        selectEl.disabled = false;
        return;
    }
    
    const target = (selectedValue || '').trim();
    const cleanTarget = target.toLowerCase().replace(/^(ខេត្ត|រាជធានី|ស្រុក|ខណ្ឌ|ក្រុង|ឃុំ|សង្កាត់|ភូមិ)\s*/u, '');
    let matched = false;
    
    items.forEach(item => {
        const opt = document.createElement('option');
        // Store the official Khmer name as value so it stores correctly in database
        opt.value = item.nameKm || item.value;
        // Display crisp Khmer name to prevent narrow column overflow
        opt.textContent = item.nameKm || item.label || item.value;
        opt.title = item.label || item.nameKm || item.value;
        opt.dataset.code = item.code || '';
        
        const optVal = (opt.value || '').trim();
        const cleanOptVal = optVal.toLowerCase().replace(/^(ខេត្ត|រាជធានី|ស្រុក|ខណ្ឌ|ក្រុង|ឃុំ|សង្កាត់|ភូមិ)\s*/u, '');
        const optEn = (item.nameEn || '').trim().toLowerCase();
        
        if (target && !matched) {
            if (
                optVal === target ||
                cleanOptVal === cleanTarget ||
                opt.dataset.code === target ||
                (optEn && optEn === target.toLowerCase()) ||
                (target.length >= 3 && optVal.includes(target)) ||
                (target.length >= 3 && target.includes(optVal))
            ) {
                opt.selected = true;
                matched = true;
            }
        }
        selectEl.appendChild(opt);
    });
    
    // Preserve custom value if not found in official list
    if (target && !matched && target !== defOpt.value) {
        const customOpt = document.createElement('option');
        customOpt.value = target;
        customOpt.textContent = target;
        customOpt.selected = true;
        selectEl.appendChild(customOpt);
    }
    
    selectEl.disabled = false;
    selectEl.dataset.selected = '';
}

async function handleProvinceChange(prefix, targetDistrictVal = null, targetCommuneVal = null, targetVillageVal = null) {
    const provEl = document.getElementById(prefix ? `${prefix}_province` : 'province');
    const distEl = document.getElementById(prefix ? `${prefix}_district` : 'district');
    const commEl = document.getElementById(prefix ? `${prefix}_commune` : 'commune');
    const villEl = document.getElementById(prefix ? `${prefix}_village` : 'village');
    
    if (!provEl || !distEl) return;
    
    const provVal = provEl.value.trim();
    const provCode = provEl.selectedOptions[0]?.dataset?.code || '';
    if (!provVal) {
        setSelectEmpty(distEl, 'ជ្រើសខេត្តជាមុន');
        setSelectEmpty(commEl, 'ជ្រើសស្រុកជាមុន');
        setSelectEmpty(villEl, 'ជ្រើសឃុំជាមុន');
        return;
    }
    
    setSelectLoading(distEl, 'កំពុងផ្ទុកស្រុក...');
    setSelectEmpty(commEl, 'ជ្រើសស្រុកជាមុន');
    setSelectEmpty(villEl, 'ជ្រើសឃុំជាមុន');
    
    const districts = await fetchLocationData(CAMBODIA_API.districts, { province: provVal, province_code: provCode });
    const desiredDistrict = targetDistrictVal !== null ? targetDistrictVal : (distEl.dataset.selected || distEl.value || '');
    populateSelectOptions(distEl, districts, desiredDistrict, 'ជ្រើសរើសស្រុក');
    
    if (distEl.value) {
        await handleDistrictChange(prefix, targetCommuneVal, targetVillageVal);
    }
}

async function handleDistrictChange(prefix, targetCommuneVal = null, targetVillageVal = null) {
    const provEl = document.getElementById(prefix ? `${prefix}_province` : 'province');
    const distEl = document.getElementById(prefix ? `${prefix}_district` : 'district');
    const commEl = document.getElementById(prefix ? `${prefix}_commune` : 'commune');
    const villEl = document.getElementById(prefix ? `${prefix}_village` : 'village');
    
    if (!distEl || !commEl) return;
    
    const provVal = provEl?.value?.trim() || '';
    const provCode = provEl?.selectedOptions[0]?.dataset?.code || '';
    const distVal = distEl.value.trim();
    const distCode = distEl.selectedOptions[0]?.dataset?.code || '';
    
    if (!distVal) {
        setSelectEmpty(commEl, 'ជ្រើសស្រុកជាមុន');
        setSelectEmpty(villEl, 'ជ្រើសឃុំជាមុន');
        return;
    }
    
    setSelectLoading(commEl, 'កំពុងផ្ទុកឃុំ...');
    setSelectEmpty(villEl, 'ជ្រើសឃុំជាមុន');
    
    const communes = await fetchLocationData(CAMBODIA_API.communes, { 
        district: distVal, 
        district_code: distCode,
        province: provVal,
        province_code: provCode
    });
    const desiredCommune = targetCommuneVal !== null ? targetCommuneVal : (commEl.dataset.selected || commEl.value || '');
    populateSelectOptions(commEl, communes, desiredCommune, 'ជ្រើសរើសឃុំ');
    
    if (commEl.value) {
        await handleCommuneChange(prefix, targetVillageVal);
    }
}

async function handleCommuneChange(prefix, targetVillageVal = null) {
    const distEl = document.getElementById(prefix ? `${prefix}_district` : 'district');
    const commEl = document.getElementById(prefix ? `${prefix}_commune` : 'commune');
    const villEl = document.getElementById(prefix ? `${prefix}_village` : 'village');
    
    if (!commEl || !villEl) return;
    
    const distVal = distEl?.value?.trim() || '';
    const distCode = distEl?.selectedOptions[0]?.dataset?.code || '';
    const commVal = commEl.value.trim();
    const commCode = commEl.selectedOptions[0]?.dataset?.code || '';
    
    if (!commVal) {
        setSelectEmpty(villEl, 'ជ្រើសឃុំជាមុន');
        return;
    }
    
    setSelectLoading(villEl, 'កំពុងផ្ទុកភូមិ...');
    
    const villages = await fetchLocationData(CAMBODIA_API.villages, { 
        commune: commVal, 
        commune_code: commCode,
        district: distVal,
        district_code: distCode
    });
    const desiredVillage = targetVillageVal !== null ? targetVillageVal : (villEl.dataset.selected || villEl.value || '');
    populateSelectOptions(villEl, villages, desiredVillage, 'ជ្រើសរើសភូមិ');
}

function registerCascadeChain(prefix, onUpdateCallback = null) {
    const provEl = document.getElementById(prefix ? `${prefix}_province` : 'province');
    const distEl = document.getElementById(prefix ? `${prefix}_district` : 'district');
    const commEl = document.getElementById(prefix ? `${prefix}_commune` : 'commune');
    const villEl = document.getElementById(prefix ? `${prefix}_village` : 'village');
    
    if (provEl) {
        provEl.addEventListener('change', async () => {
            await handleProvinceChange(prefix, '', '', '');
            provEl.classList.remove('is-invalid');
            if (typeof onUpdateCallback === 'function') onUpdateCallback();
        });
    }
    if (distEl) {
        distEl.addEventListener('change', async () => {
            await handleDistrictChange(prefix, '', '');
            distEl.classList.remove('is-invalid');
            if (typeof onUpdateCallback === 'function') onUpdateCallback();
        });
    }
    if (commEl) {
        commEl.addEventListener('change', async () => {
            await handleCommuneChange(prefix, '');
            commEl.classList.remove('is-invalid');
            if (typeof onUpdateCallback === 'function') onUpdateCallback();
        });
    }
    if (villEl) {
        villEl.addEventListener('change', () => {
            villEl.classList.remove('is-invalid');
            if (typeof onUpdateCallback === 'function') onUpdateCallback();
        });
    }
    
    // Initial load if province has existing value
    if (provEl && provEl.value) {
        handleProvinceChange(prefix, null, null, null);
    }
}

function initCascadingLocations() {
    // 1. Primary / Current Address Chain
    registerCascadeChain('', () => {
        if (typeof syncPermanentAddress === 'function') syncPermanentAddress();
    });

    // 2. Guardian Address Chain (Students)
    registerCascadeChain('guardian', () => {
        if (typeof compileGuardianAddress === 'function') compileGuardianAddress();
    });

    // 3. Permanent Address Chain (Employees)
    registerCascadeChain('permanent', () => {
        if (typeof updateCompiledPermanentAddress === 'function') updateCompiledPermanentAddress();
    });

    // 4. Birth Place Chain (Employees)
    registerCascadeChain('birth');
}

/**
 * Compile guardian address string from individual cascade selects
 */
function compileGuardianAddress() {
    const v = document.getElementById('guardian_village')?.value?.trim();
    const c = document.getElementById('guardian_commune')?.value?.trim();
    const d = document.getElementById('guardian_district')?.value?.trim();
    const p = document.getElementById('guardian_province')?.value?.trim();

    let parts = [];
    if (v) parts.push('ភូមិ ' + v);
    if (c) parts.push('ឃុំ/សង្កាត់ ' + c);
    if (d) parts.push('ស្រុក/ខណ្ឌ ' + d);
    if (p) parts.push(p);

    const hiddenInput = document.getElementById('guardian_address');
    if (hiddenInput) {
        hiddenInput.value = parts.join(', ');
    }
}

/**
 * Enhanced copyStudentAddressToGuardian function that cascades all 4 dropdowns
 */
async function copyStudentAddressToGuardian() {
    const sProvince = document.getElementById('province')?.value || '';
    const sDistrict = document.getElementById('district')?.value || '';
    const sCommune = document.getElementById('commune')?.value || '';
    const sVillage = document.getElementById('village')?.value || '';

    const gProvEl = document.getElementById('guardian_province');
    if (gProvEl) {
        gProvEl.value = sProvince;
        await handleProvinceChange('guardian', sDistrict, sCommune, sVillage);
    }

    if (typeof compileGuardianAddress === 'function') {
        compileGuardianAddress();
    }
}

/**
 * Enhanced copyCurrentAddressToPermanent for Employee Address
 */
async function copyCurrentAddressToPermanent() {
    const currStreet = document.getElementById('current_address')?.value || '';
    const currProv = document.getElementById('province')?.value || '';
    const currDist = document.getElementById('district')?.value || '';
    const currComm = document.getElementById('commune')?.value || '';
    const currVill = document.getElementById('village')?.value || '';

    const permStreet = document.getElementById('permanent_street');
    const permProv = document.getElementById('permanent_province');

    if (permStreet) permStreet.value = currStreet;
    if (permProv) {
        permProv.value = currProv;
        await handleProvinceChange('permanent', currDist, currComm, currVill);
    }

    if (typeof updateCompiledPermanentAddress === 'function') {
        updateCompiledPermanentAddress();
    }
}

function updateCompiledPermanentAddress() {
    const street = document.getElementById('permanent_street')?.value?.trim() || '';
    const village = document.getElementById('permanent_village')?.value?.trim() || '';
    const commune = document.getElementById('permanent_commune')?.value?.trim() || '';
    const district = document.getElementById('permanent_district')?.value?.trim() || '';
    const province = document.getElementById('permanent_province')?.value?.trim() || '';

    const parts = [];
    if (street) parts.push(street);
    if (village) parts.push('ភូមិ ' + village);
    if (commune) parts.push('ឃុំ/សង្កាត់ ' + commune);
    if (district) parts.push('ស្រុក/ខណ្ឌ ' + district);
    if (province) parts.push('ខេត្ត/រាជធានី ' + province);

    const hiddenPerm = document.getElementById('permanent_address');
    if (hiddenPerm) {
        hiddenPerm.value = parts.join(', ');
    }
}

function toggleSameAddress(checkbox) {
    const permStreet = document.getElementById('permanent_street');
    const permProv = document.getElementById('permanent_province');
    const permDist = document.getElementById('permanent_district');
    const permComm = document.getElementById('permanent_commune');
    const permVill = document.getElementById('permanent_village');

    if (checkbox.checked) {
        copyCurrentAddressToPermanent();
        if (permStreet) { permStreet.readOnly = true; permStreet.classList.add('bg-light'); }
        [permProv, permDist, permComm, permVill].forEach(el => {
            if (el) {
                el.style.pointerEvents = 'none';
                el.style.backgroundColor = '#e9ecef';
            }
        });
    } else {
        if (permStreet) { permStreet.readOnly = false; permStreet.classList.remove('bg-light'); }
        [permProv, permDist, permComm, permVill].forEach(el => {
            if (el) {
                el.style.pointerEvents = '';
                el.style.backgroundColor = '';
            }
        });
    }
}

function syncPermanentAddress() {
    const checkbox = document.getElementById('sameAsCurrentAddress');
    if (checkbox && checkbox.checked) {
        copyCurrentAddressToPermanent();
    }
}

document.addEventListener('DOMContentLoaded', initCascadingLocations);
</script>
<?php /**PATH D:\School\laravel\resources\views/students/partials/cambodia_districts_js.blade.php ENDPATH**/ ?>