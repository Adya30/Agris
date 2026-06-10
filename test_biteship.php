<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = config('services.biteship.key');
$baseUrl = config('services.biteship.url') ?: 'https://api.biteship.com/v1';

echo "API Key: " . $apiKey . PHP_EOL;
echo "Base URL: " . $baseUrl . PHP_EOL;

$response = Illuminate\Support\Facades\Http::withToken($apiKey)->post("$baseUrl/locations", [
    'name' => 'Alamat Penjemputan Admin',
    'contact_name' => 'Admin Utama',
    'contact_phone' => '08123456789',
    'address' => 'Jl. Manyar Gg. Kelapa, Puring, Slawu, Patrang, Kabupaten Jember, Jawa Timur',
    'postal_code' => '68111',
    'latitude' => -8.1724,
    'longitude' => 113.7005,
    'type' => 'origin'
]);

echo "Status: " . $response->status() . PHP_EOL;
echo "Body: " . $response->body() . PHP_EOL;
