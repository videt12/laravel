<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CambodiaLocationController extends Controller
{
    /**
     * @var array<int, array<string, string>>|null
     */
    protected static ?array $provinces = null;

    /**
     * @var array<int, array<string, string>>|null
     */
    protected static ?array $districts = null;

    /**
     * @var array<string, array<int, array<string, string>>>|null
     */
    protected static ?array $districtsByProvince = null;

    /**
     * @var array<int, array<string, string>>|null
     */
    protected static ?array $communes = null;

    /**
     * @var array<string, array<int, array<string, string>>>|null
     */
    protected static ?array $communesByDistrict = null;

    /**
     * @var array<string, array<int, array<string, string>>>|null
     */
    protected static ?array $villagesByCommune = null;

    protected function loadData(): void
    {
        if (self::$provinces === null) {
            $path = public_path('data/cambodia');

            if (File::exists("{$path}/provinces.json")) {
                self::$provinces = json_decode((string) File::get("{$path}/provinces.json"), true) ?: [];
            }
            if (File::exists("{$path}/districts.json")) {
                self::$districts = json_decode((string) File::get("{$path}/districts.json"), true) ?: [];
            }
            if (File::exists("{$path}/districts_by_province.json")) {
                self::$districtsByProvince = json_decode((string) File::get("{$path}/districts_by_province.json"), true) ?: [];
            }
            if (File::exists("{$path}/communes.json")) {
                self::$communes = json_decode((string) File::get("{$path}/communes.json"), true) ?: [];
            }
            if (File::exists("{$path}/communes_by_district.json")) {
                self::$communesByDistrict = json_decode((string) File::get("{$path}/communes_by_district.json"), true) ?: [];
            }
            if (File::exists("{$path}/villages_by_commune.json")) {
                self::$villagesByCommune = json_decode((string) File::get("{$path}/villages_by_commune.json"), true) ?: [];
            }
        }
    }

    public function getDistricts(Request $request): JsonResponse
    {
        $this->loadData();
        $provinceParam = trim((string) $request->input('province', ''));
        $provinceCodeParam = trim((string) $request->input('province_code', ''));
        if ($provinceParam === '' && $provinceCodeParam === '') {
            return response()->json([]);
        }

        $provinceCode = $provinceCodeParam !== '' ? $provinceCodeParam : $this->resolveProvinceCode($provinceParam);
        if (! $provinceCode) {
            return response()->json([]);
        }

        $districts = self::$districtsByProvince[$provinceCode] ?? self::$districtsByProvince[str_pad((string) $provinceCode, 2, '0', STR_PAD_LEFT)] ?? [];
        $result = array_map(function ($d) {
            return [
                'code' => $d['code'],
                'nameKm' => $d['nameKm'],
                'nameEn' => $d['nameEn'],
                'label' => $d['nameKm'].($d['nameEn'] ? ' ('.$d['nameEn'].')' : ''),
            ];
        }, $districts);

        return response()->json($result);
    }

    public function getCommunes(Request $request): JsonResponse
    {
        $this->loadData();
        $districtParam = trim((string) $request->input('district', ''));
        $districtCodeParam = trim((string) $request->input('district_code', ''));
        $provinceParam = trim((string) $request->input('province', ''));

        if ($districtParam === '' && $districtCodeParam === '') {
            return response()->json([]);
        }

        $districtCode = null;
        if ($districtCodeParam !== '') {
            $districtCode = $districtCodeParam;
        } else {
            $provinceCode = $provinceParam !== '' ? $this->resolveProvinceCode($provinceParam) : null;
            $districtCode = $this->resolveDistrictCode($districtParam, $provinceCode);
        }

        if (! $districtCode) {
            return response()->json([]);
        }

        $communes = self::$communesByDistrict[$districtCode]
            ?? self::$communesByDistrict[(string) $districtCode]
            ?? self::$communesByDistrict[str_pad((string) $districtCode, 4, '0', STR_PAD_LEFT)]
            ?? [];

        $result = array_map(function ($c) {
            return [
                'code' => $c['code'],
                'nameKm' => $c['nameKm'],
                'nameEn' => $c['nameEn'],
                'label' => 'ឃុំ/សង្កាត់ '.$c['nameKm'].($c['nameEn'] ? ' ('.$c['nameEn'].')' : ''),
            ];
        }, $communes);

        return response()->json($result);
    }

    public function getVillages(Request $request): JsonResponse
    {
        $this->loadData();
        $communeParam = trim((string) $request->input('commune', ''));
        $communeCodeParam = trim((string) $request->input('commune_code', ''));
        $districtParam = trim((string) $request->input('district', ''));

        if ($communeParam === '' && $communeCodeParam === '') {
            return response()->json([]);
        }

        $communeCode = null;
        if ($communeCodeParam !== '') {
            $communeCode = $communeCodeParam;
        } else {
            $communeCode = $this->resolveCommuneCode($communeParam, $districtParam);
        }

        if (! $communeCode) {
            return response()->json([]);
        }

        $villages = self::$villagesByCommune[$communeCode]
            ?? self::$villagesByCommune[(string) $communeCode]
            ?? self::$villagesByCommune[str_pad((string) $communeCode, 6, '0', STR_PAD_LEFT)]
            ?? [];

        $result = array_map(function ($v) {
            return [
                'code' => $v['code'],
                'nameKm' => $v['nameKm'],
                'nameEn' => $v['nameEn'],
                'label' => 'ភូមិ '.$v['nameKm'].($v['nameEn'] ? ' ('.$v['nameEn'].')' : ''),
            ];
        }, $villages);

        return response()->json($result);
    }

    protected function resolveProvinceCode(string $input): ?string
    {
        if (preg_match('/^\d{2}$/', $input)) {
            return $input;
        }

        $clean = mb_strtolower(trim((string) preg_replace('/^(ខេត្ត|រាជធានី)\s*/u', '', $input)));
        $normalizedInput = str_ireplace(['é', 'è', 'ê', 'ë'], 'e', $clean);

        foreach (self::$provinces ?? [] as $p) {
            if ($p['code'] === $input) {
                return $p['code'];
            }
            $cleanKm = mb_strtolower(trim((string) preg_replace('/^(ខេត្ត|រាជធានី)\s*/u', '', $p['nameKm'])));
            $cleanEn = mb_strtolower(trim($p['nameEn']));
            $normalizedEn = str_ireplace(['é', 'è', 'ê', 'ë'], 'e', $cleanEn);

            if ($cleanKm === $clean || $cleanEn === $clean || $normalizedEn === $normalizedInput) {
                return $p['code'];
            }
            if ($clean !== '' && (mb_strpos($p['nameKm'], $clean) !== false || stripos($p['nameEn'], $clean) !== false || stripos($normalizedEn, $normalizedInput) !== false)) {
                return $p['code'];
            }
        }

        return null;
    }

    protected function resolveDistrictCode(string $input, ?string $provinceCode = null): ?string
    {
        if (preg_match('/^\d{4}$/', $input)) {
            return $input;
        }

        $clean = mb_strtolower(trim((string) preg_replace('/^(ស្រុក|ខណ្ឌ|ក្រុង)\s*/u', '', $input)));

        if ($provinceCode !== null) {
            foreach (self::$districts ?? [] as $d) {
                if ($d['provinceCode'] !== $provinceCode) {
                    continue;
                }
                if ($d['code'] === $input) {
                    return $d['code'];
                }
                $cleanKm = mb_strtolower(trim((string) preg_replace('/^(ស្រុក|ខណ្ឌ|ក្រុង)\s*/u', '', $d['nameKm'])));
                if ($cleanKm === $clean || mb_strtolower($d['nameEn']) === $clean) {
                    return $d['code'];
                }
                if ($clean !== '' && (mb_strpos($d['nameKm'], $clean) !== false || stripos($d['nameEn'], $clean) !== false)) {
                    return $d['code'];
                }
            }
        }

        foreach (self::$districts ?? [] as $d) {
            if ($d['code'] === $input) {
                return $d['code'];
            }
            $cleanKm = mb_strtolower(trim((string) preg_replace('/^(ស្រុក|ខណ្ឌ|ក្រុង)\s*/u', '', $d['nameKm'])));
            if ($cleanKm === $clean || mb_strtolower($d['nameEn']) === $clean) {
                return $d['code'];
            }
            if ($clean !== '' && (mb_strpos($d['nameKm'], $clean) !== false || stripos($d['nameEn'], $clean) !== false)) {
                return $d['code'];
            }
        }

        return null;
    }

    protected function resolveCommuneCode(string $input, ?string $districtParam = null): ?string
    {
        if (preg_match('/^\d{6}$/', $input)) {
            return $input;
        }

        $clean = mb_strtolower(trim((string) preg_replace('/^(ឃុំ|សង្កាត់|ឃុំ\/សង្កាត់)\s*/u', '', $input)));
        $districtCode = $districtParam ? $this->resolveDistrictCode($districtParam) : null;

        if ($districtCode !== null) {
            foreach (self::$communes ?? [] as $c) {
                if ($c['districtCode'] !== $districtCode) {
                    continue;
                }
                if ($c['code'] === $input) {
                    return $c['code'];
                }
                $cleanKm = mb_strtolower(trim((string) preg_replace('/^(ឃុំ|សង្កាត់|ឃុំ\/សង្កាត់)\s*/u', '', $c['nameKm'])));
                if ($cleanKm === $clean || mb_strtolower($c['nameEn']) === $clean) {
                    return $c['code'];
                }
                if ($clean !== '' && (mb_strpos($c['nameKm'], $clean) !== false || stripos($c['nameEn'], $clean) !== false)) {
                    return $c['code'];
                }
            }
        }

        foreach (self::$communes ?? [] as $c) {
            if ($c['code'] === $input) {
                return $c['code'];
            }
            $cleanKm = mb_strtolower(trim((string) preg_replace('/^(ឃុំ|សង្កាត់|ឃុំ\/សង្កាត់)\s*/u', '', $c['nameKm'])));
            if ($cleanKm === $clean || mb_strtolower($c['nameEn']) === $clean) {
                return $c['code'];
            }
            if ($clean !== '' && (mb_strpos($c['nameKm'], $clean) !== false || stripos($c['nameEn'], $clean) !== false)) {
                return $c['code'];
            }
        }

        return null;
    }
}
