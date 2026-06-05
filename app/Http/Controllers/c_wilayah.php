<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class c_wilayah extends Controller
{
    protected $baseUrl = 'https://wilayah.id/api';

    public function getProvinsi()
    {
        $res = Http::get("{$this->baseUrl}/provinces.json");
        $data = $res->json()['data'] ?? [];
        $transformed = collect($data)->map(function ($item) {
            return [
                'id' => $item['code'],
                'name' => $item['name']
            ];
        });
        return response()->json($transformed);
    }

    public function getKabupaten(string $id)
    {
        $res = Http::get("{$this->baseUrl}/regencies/{$id}.json");
        $data = $res->json()['data'] ?? [];
        $transformed = collect($data)->map(function ($item) {
            return [
                'id' => $item['code'],
                'name' => $item['name']
            ];
        });
        return response()->json($transformed);
    }

    public function getKecamatan(string $id)
    {
        $res = Http::get("{$this->baseUrl}/districts/{$id}.json");
        $data = $res->json()['data'] ?? [];
        $transformed = collect($data)->map(function ($item) {
            return [
                'id' => $item['code'],
                'name' => $item['name']
            ];
        });
        return response()->json($transformed);
    }

    public function getDesa(string $id)
    {
        $res = Http::get("{$this->baseUrl}/villages/{$id}.json");
        $data = $res->json()['data'] ?? [];
        $transformed = collect($data)->map(function ($item) {
            return [
                'id' => $item['code'],
                'name' => $item['name']
            ];
        });
        return response()->json($transformed);
    }
}
