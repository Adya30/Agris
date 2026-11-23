<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\PotentiallyTranslatedString;

class SecurePdfFile implements ValidationRule
{
    protected int $maxSizeInKb;

    public function __construct(int $maxSizeInKb = 1024)
    {
        $this->maxSizeInKb = $maxSizeInKb;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('File dokumen tidak valid.');

            return;
        }

        if (! $value->isValid()) {
            $errorCode = $value->getError();
            if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                $maxMb = round($this->maxSizeInKb / 1024);
                $fail("Ukuran file dokumen melebihi batas upload konfigurasi server (upload_max_filesize di php.ini). Batas aplikasi: {$maxMb}MB.");
            } else {
                $fail('File dokumen gagal diunggah ke server (kode status: '.$errorCode.').');
            }

            return;
        }

        // 1. Validasi ukuran file (maksimal)
        if ($value->getSize() > $this->maxSizeInKb * 1024) {
            $maxMb = round($this->maxSizeInKb / 1024);
            $fail("Ukuran dokumen MOU maksimal {$maxMb}MB.");

            return;
        }

        // 2. Validasi ekstensi nama file (case-insensitive)
        $clientExt = strtolower($value->getClientOriginalExtension() ?: '');
        if ($clientExt !== 'pdf') {
            $fail('Format dokumen harus berupa file PDF.');

            return;
        }

        // 3. Cegah double extension attack (misal: exploit.php.pdf)
        $clientName = strtolower($value->getClientOriginalName() ?: '');
        if (preg_match('/\.(php|phtml|phar|sh|exe|cgi|pl|py|asp|jsp)\.[a-z0-9]+$/i', $clientName)) {
            $fail('Nama file dokumen mengandung ekstensi berbahaya atau tidak diizinkan.');

            return;
        }

        // 4. Validasi struktur/header PDF & pencegahan injeksi kode
        $path = $value->getRealPath();
        if ($path && file_exists($path) && is_readable($path)) {
            $content = @file_get_contents($path);
            if ($content !== false && strlen($content) > 0) {
                // Dokumen PDF asli harus memiliki identifier PDF di 1024 byte awal
                if (! str_contains(substr($content, 0, 1024), '%PDF-')) {
                    $fail('Header file tidak sesuai dengan standar dokumen PDF resmi.');

                    return;
                }

                // Blokir sisipan kode eksekusi server PHP atau perintah peluncuran eksternal PDF
                $dangerousPatterns = [
                    '<?php', '<?=', '/Launch',
                ];

                foreach ($dangerousPatterns as $pattern) {
                    if (stripos($content, $pattern) !== false) {
                        $fail('Dokumen ditolak karena mengandung kode atau perintah berbahaya.');

                        return;
                    }
                }
            }
        }
    }
}
