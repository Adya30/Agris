<?php

use App\Models\Blog;
use App\Models\Chat;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

test('storage_url helper handles null, external, and local paths correctly', function () {
    expect(storage_url(null))->toBeNull();
    expect(storage_url(''))->toBeNull();

    // External URLs
    expect(storage_url('https://example.com/image.jpg'))->toBe('https://example.com/image.jpg');
    expect(storage_url('http://example.com/photo.png'))->toBe('http://example.com/photo.png');

    // Local / public disk URLs
    Config::set('filesystems.default', 'public');
    $url = storage_url('produk/test.jpg');
    expect($url)->toContain('storage/produk/test.jpg');

    // Strips redundant storage/ prefix
    $urlPrefixed = storage_url('storage/produk/test.jpg');
    expect($urlPrefixed)->toContain('storage/produk/test.jpg');
    expect($urlPrefixed)->not->toContain('storage/storage/');
});

test('storage_url helper works with s3 / object storage configuration', function () {
    Config::set('filesystems.default', 's3');
    Config::set('filesystems.disks.s3.url', 'https://pub-r2.example.com');
    Storage::forgetDisk('s3');

    $s3Url = storage_url('produk/test.jpg');
    expect($s3Url)->toBe('https://pub-r2.example.com/produk/test.jpg');
});

test('model accessors resolve storage urls properly', function () {
    Config::set('filesystems.default', 'public');

    $produk = new Produk(['fotoProduk' => 'produk/beras.jpg']);
    expect($produk->foto_url)->toContain('storage/produk/beras.jpg');

    $blog = new Blog(['fotoBlog' => 'blog/tips.jpg']);
    expect($blog->foto_url)->toContain('storage/blog/tips.jpg');

    $user = new User(['fotoProfil' => 'profile_photos/user.jpg']);
    expect($user->foto_profil_url)->toContain('storage/profile_photos/user.jpg');

    $chat = new Chat(['foto_chat' => 'chats/attachment.png']);
    expect($chat->foto_chat_url)->toContain('storage/chats/attachment.png');
});
