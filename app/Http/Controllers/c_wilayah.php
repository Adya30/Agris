<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class c_wilayah extends Controller
{
    protected $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function getProvinsi()
    {
        $res = Http::get("{$this->baseUrl}/provinces.json");
        return response()->json($res->json() ?? []);
    }

    public function getKabupaten($id)
    {
        $res = Http::get("{$this->baseUrl}/regencies/{$id}.json");
        return response()->json($res->json() ?? []);
    }

    public function getKecamatan($id)
    {
        $res = Http::get("{$this->baseUrl}/districts/{$id}.json");
        return response()->json($res->json() ?? []);
    }

    public function getDesa($id)
    {
        $res = Http::get("{$this->baseUrl}/villages/{$id}.json");
        return response()->json($res->json() ?? []);
    }
}
