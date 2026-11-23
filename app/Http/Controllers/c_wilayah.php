<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class c_wilayah extends Controller
{
    protected string $baseUrl = 'https://wilayah.id/api';

    public function getProvinsi()
    {
        $cacheKey = 'wilayah_provinces_v2';
        $cached = Cache::get($cacheKey);
        if (! empty($cached) && is_array($cached)) {
            return response()->json($cached);
        }

        try {
            $res = Http::timeout(8)->retry(2, 200)->get("{$this->baseUrl}/provinces.json");
            if ($res->successful()) {
                $data = $res->json()['data'] ?? [];
                if (! empty($data)) {
                    $transformed = collect($data)->map(function ($item) {
                        return [
                            'id' => (string) $item['code'],
                            'name' => (string) $item['name'],
                        ];
                    })->all();

                    Cache::put($cacheKey, $transformed, 86400 * 30);

                    return response()->json($transformed);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Wilayah API getProvinsi error: '.$e->getMessage());
        }

        $local = Provinsi::all()->map(function ($item) {
            return [
                'id' => (string) $item->id,
                'name' => (string) $item->namaProvinsi,
            ];
        })->all();

        return response()->json($local);
    }

    public function getKabupaten(string $id)
    {
        $cacheKey = 'wilayah_regencies_v2_'.$id;
        $cached = Cache::get($cacheKey);
        if (! empty($cached) && is_array($cached)) {
            return response()->json($cached);
        }

        try {
            $res = Http::timeout(8)->retry(2, 200)->get("{$this->baseUrl}/regencies/{$id}.json");
            if ($res->successful()) {
                $data = $res->json()['data'] ?? [];
                if (! empty($data)) {
                    $transformed = collect($data)->map(function ($item) {
                        return [
                            'id' => (string) $item['code'],
                            'name' => (string) $item['name'],
                        ];
                    })->all();

                    Cache::put($cacheKey, $transformed, 86400 * 30);

                    return response()->json($transformed);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Wilayah API getKabupaten {$id} error: ".$e->getMessage());
        }

        $local = Kabupaten::where('provinsiId', $id)->get()->map(function ($item) {
            return [
                'id' => (string) $item->id,
                'name' => (string) $item->namaKabupaten,
            ];
        })->all();

        return response()->json($local);
    }

    public function getKecamatan(string $id)
    {
        $cacheKey = 'wilayah_districts_v2_'.$id;
        $cached = Cache::get($cacheKey);
        if (! empty($cached) && is_array($cached)) {
            return response()->json($cached);
        }

        try {
            $res = Http::timeout(8)->retry(2, 200)->get("{$this->baseUrl}/districts/{$id}.json");
            if ($res->successful()) {
                $data = $res->json()['data'] ?? [];
                if (! empty($data)) {
                    $transformed = collect($data)->map(function ($item) {
                        return [
                            'id' => (string) $item['code'],
                            'name' => (string) $item['name'],
                        ];
                    })->all();

                    Cache::put($cacheKey, $transformed, 86400 * 30);

                    return response()->json($transformed);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Wilayah API getKecamatan {$id} error: ".$e->getMessage());
        }

        $local = Kecamatan::where('kabupatenId', $id)->get()->map(function ($item) {
            return [
                'id' => (string) $item->id,
                'name' => (string) $item->namaKecamatan,
            ];
        })->all();

        return response()->json($local);
    }

    public function getDesa(string $id)
    {
        $cacheKey = 'wilayah_villages_v2_'.$id;
        $cached = Cache::get($cacheKey);
        if (! empty($cached) && is_array($cached)) {
            return response()->json($cached);
        }

        try {
            $res = Http::timeout(8)->retry(2, 200)->get("{$this->baseUrl}/villages/{$id}.json");
            if ($res->successful()) {
                $data = $res->json()['data'] ?? [];
                if (! empty($data)) {
                    $transformed = collect($data)->map(function ($item) {
                        return [
                            'id' => (string) $item['code'],
                            'name' => (string) $item['name'],
                        ];
                    })->all();

                    Cache::put($cacheKey, $transformed, 86400 * 30);

                    return response()->json($transformed);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Wilayah API getDesa {$id} error: ".$e->getMessage());
        }

        $local = Desa::where('kecamatanId', $id)->get()->map(function ($item) {
            return [
                'id' => (string) $item->id,
                'name' => (string) $item->namaDesa,
            ];
        })->all();

        return response()->json($local);
    }
}
