<?php

use App\Models\Kemitraan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('authenticated user can upload mou document and status updates to Menunggu Verifikasi MOU', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'namaLengkap' => 'Agen Test',
        'noTelp' => '081234567890',
        'detailAlamat' => 'Jl. Test No. 1',
        'isAdmin' => false,
    ]);

    $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R >>\nendobj\nxref\n0 4\n0000000000 65535 f\n0000000010 00000 n\n0000000060 00000 n\n0000000120 00000 n\ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n180\n%%EOF";
    $file = UploadedFile::fake()->createWithContent('mou_signed.pdf', $pdfContent);

    $response = $this->actingAs($user)->post(route('kemitraan.store'), [
        'fileKemitraan' => $file,
    ]);

    $response->assertRedirect(route('kemitraan.index'));
    $response->assertSessionHas('success');

    $kemitraan = Kemitraan::where('userId', $user->id)->first();
    expect($kemitraan)->not->toBeNull();
    expect($kemitraan->statusPengajuan)->toBe('Menunggu Verifikasi MOU');
    expect($kemitraan->fileKemitraan)->not->toBeNull();
    Storage::disk('public')->assertExists($kemitraan->fileKemitraan);
});
