<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class c_wilayah extends Controller
{
    protected $baseUrl = 'https://wilayah.id/api';

    public function getProvinsi()
    {
        $transformed = Cache::remember('wilayah_provinces', 86400 * 7, function () {
            $res = Http::timeout(10)->get("{$this->baseUrl}/provinces.json");
            $data = $res->json()['data'] ?? [];

            return collect($data)->map(function ($item) {
                return [
                    'id' => $item['code'],
                    'name' => $item['name'],
                ];
            })->all();
        });

        return response()->json($transformed);
    }

    public function getKabupaten(string $id)
    {
        $transformed = Cache::remember('wilayah_regencies_'.$id, 86400 * 7, function () use ($id) {
            $res = Http::timeout(10)->get("{$this->baseUrl}/regencies/{$id}.json");
            $data = $res->json()['data'] ?? [];

            return collect($data)->map(function ($item) {
                return [
                    'id' => $item['code'],
                    'name' => $item['name'],
                ];
            })->all();
        });

        return response()->json($transformed);
    }

    public function getKecamatan(string $id)
    {
        $transformed = Cache::remember('wilayah_districts_'.$id, 86400 * 7, function () use ($id) {
            $res = Http::timeout(10)->get("{$this->baseUrl}/districts/{$id}.json");
            $data = $res->json()['data'] ?? [];

            return collect($data)->map(function ($item) {
                return [
                    'id' => $item['code'],
                    'name' => $item['name'],
                ];
            })->all();
        });

        return response()->json($transformed);
    }

    public function getDesa(string $id)
    {
        $transformed = Cache::remember('wilayah_villages_'.$id, 86400 * 7, function () use ($id) {
            $res = Http::timeout(10)->get("{$this->baseUrl}/villages/{$id}.json");
            $data = $res->json()['data'] ?? [];

            return collect($data)->map(function ($item) {
                return [
                    'id' => $item['code'],
                    'name' => $item['name'],
                ];
            })->all();
        });

        return response()->json($transformed);
    }
}
