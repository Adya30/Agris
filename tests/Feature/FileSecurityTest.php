<?php

use App\Rules\SecureImageFile;
use App\Rules\SecurePdfFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

test('SecurePdfFile accepts valid genuine PDF document', function () {
    $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R >>\nendobj\nxref\n0 4\n0000000000 65535 f \n0000000010 00000 n \n0000000060 00000 n \n0000000124 00000 n \ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n173\n%%EOF";

    $file = UploadedFile::fake()->createWithContent('document.pdf', $pdfContent);

    $validator = Validator::make(['doc' => $file], [
        'doc' => [new SecurePdfFile(1024)],
    ]);

    expect($validator->passes())->toBeTrue();
});

test('SecurePdfFile rejects fake PDF or script renamed as PDF', function () {
    $scriptTag = '<'.'?'.'php phpinfo(); ?'.'>';
    $fakePdf = UploadedFile::fake()->createWithContent('exploit.pdf', $scriptTag);

    $validator = Validator::make(['doc' => $fakePdf], [
        'doc' => [new SecurePdfFile(1024)],
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('doc'))->toContain('Header file tidak sesuai dengan standar dokumen PDF resmi');
});

test('SecurePdfFile rejects PDF containing embedded dangerous executable tags', function () {
    $dangerousPdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n/Launch << /F (cmd.exe) >>\n%%EOF";

    $file = UploadedFile::fake()->createWithContent('malicious.pdf', $dangerousPdf);

    $validator = Validator::make(['doc' => $file], [
        'doc' => [new SecurePdfFile(1024)],
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('doc'))->toContain('Dokumen ditolak karena mengandung kode');
});

test('SecurePdfFile rejects double extension attacks', function () {
    $pdfContent = "%PDF-1.4\n%%EOF";
    $file = UploadedFile::fake()->createWithContent('exploit.php.pdf', $pdfContent);

    $validator = Validator::make(['doc' => $file], [
        'doc' => [new SecurePdfFile(1024)],
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('doc'))->toContain('mengandung ekstensi berbahaya');
});

test('SecureImageFile accepts genuine image and rejects fake image', function () {
    $validImage = UploadedFile::fake()->image('avatar.png', 10, 10);

    $validator = Validator::make(['image' => $validImage], [
        'image' => [new SecureImageFile(1024)],
    ]);

    expect($validator->passes())->toBeTrue();

    $fakeImage = UploadedFile::fake()->createWithContent('fake.png', 'Not a real PNG at all');

    $failValidator = Validator::make(['image' => $fakeImage], [
        'image' => [new SecureImageFile(1024)],
    ]);

    expect($failValidator->fails())->toBeTrue();
});

test('SecureImageFile rejects polyglot image containing embedded script tags', function () {
    $validImage = UploadedFile::fake()->image('test.png', 10, 10);
    $realContent = file_get_contents($validImage->getRealPath());
    $payloadTag = '<'.'?'."php echo 'test'; ?".'>';
    $polyglotContent = $realContent."\n".$payloadTag;

    $polyglotFile = UploadedFile::fake()->createWithContent('polyglot.png', $polyglotContent);

    $validator = Validator::make(['image' => $polyglotFile], [
        'image' => [new SecureImageFile(1024)],
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('image'))->toContain('mengandung sisipan kode terlarang');
});

test('SecureImageFile rejects double extension attack', function () {
    $validImage = UploadedFile::fake()->image('test.png', 10, 10);
    $realContent = file_get_contents($validImage->getRealPath());
    $doubleExtFile = UploadedFile::fake()->createWithContent('avatar.php.png', $realContent);

    $validator = Validator::make(['image' => $doubleExtFile], [
        'image' => [new SecureImageFile(1024)],
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('image'))->toContain('ekstensi ganda');
});

test('SecureImageFile accepts WhatsApp downloaded images with dots in timestamp and jfif extension', function () {
    $validImage = UploadedFile::fake()->image('WhatsApp Image 2026-11-23 at 10.30.15.jpeg', 100, 100);

    $validator = Validator::make(['image' => $validImage], [
        'image' => [new SecureImageFile(5120)],
    ]);

    expect($validator->passes())->toBeTrue();

    $jfifImage = UploadedFile::fake()->image('WhatsApp Image 2026-11-23 at 12.15.00.jfif', 100, 100);

    $jfifValidator = Validator::make(['image' => $jfifImage], [
        'image' => [new SecureImageFile(5120)],
    ]);

    expect($jfifValidator->passes())->toBeTrue();
});
