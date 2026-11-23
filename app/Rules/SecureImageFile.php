<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\PotentiallyTranslatedString;

class SecureImageFile implements ValidationRule
{
    protected int $maxSizeInKb;

    protected array $allowedExtensions;

    public function __construct(int $maxSizeInKb = 1024, array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'jfif', 'gif', 'bmp', 'heic', 'heif', 'svg'])
    {
        $this->maxSizeInKb = $maxSizeInKb;
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('File gambar tidak valid.');

            return;
        }

        if (! $value->isValid()) {
            $errorCode = $value->getError();
            if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                $maxMb = round($this->maxSizeInKb / 1024);
                $fail("Ukuran file gambar melebihi batas upload konfigurasi server (upload_max_filesize di php.ini). Batas aplikasi: {$maxMb}MB.");
            } else {
                $fail('File gambar gagal diunggah ke server (kode status: '.$errorCode.').');
            }

            return;
        }

        // 1. Validasi ukuran file
        if ($value->getSize() > $this->maxSizeInKb * 1024) {
            $maxMb = round($this->maxSizeInKb / 1024);
            $fail("Ukuran gambar maksimal {$maxMb}MB.");

            return;
        }

        // 2. Validasi ekstensi nama file (case-insensitive)
        $clientExt = strtolower($value->getClientOriginalExtension() ?: '');
        if (! in_array($clientExt, $this->allowedExtensions, true)) {
            $fail('Format gambar hanya diperbolehkan: '.strtoupper(implode(', ', $this->allowedExtensions)).'.');

            return;
        }

        // 3. Cegah double extension attack (misal: image.php.png atau shell.phtml.jpg)
        $clientName = strtolower($value->getClientOriginalName() ?: '');
        if (preg_match('/\.(php|phtml|phar|sh|exe|cgi|pl|py|asp|jsp)\.[a-z0-9]+$/i', $clientName)) {
            $fail('Nama file gambar mengandung ekstensi ganda yang mencurigakan.');

            return;
        }

        $path = $value->getRealPath();
        if ($path && file_exists($path) && is_readable($path)) {
            // 4. Verifikasi format raster (bukan SVG): pastikan berkas adalah gambar valid dan memiliki dimensi
            if ($clientExt !== 'svg' && ! in_array($clientExt, ['heic', 'heif'], true)) {
                $imageInfo = @getimagesize($path);
                if ($imageInfo === false || empty($imageInfo[0]) || empty($imageInfo[1])) {
                    $fail('File terdeteksi bukan file gambar valid atau file mengalami kerusakan.');

                    return;
                }
            }

            // 5. Pencegahan injeksi eksekusi PHP atau SVG XSS
            $content = @file_get_contents($path);
            if ($content !== false && strlen($content) > 0) {
                if ($clientExt !== 'svg') {
                    // Hanya blokir tag pembuka eksekusi PHP nyata; hindari false-positive substring pendek seperti <% pada binary stream
                    if (stripos($content, '<?php') !== false || stripos($content, '<?=\s') !== false || stripos($content, '<?= ') !== false) {
                        $fail('Gambar ditolak karena terdeteksi mengandung sisipan kode terlarang.');

                        return;
                    }
                } else {
                    // Khusus file SVG (vektor XML): cegah XSS melalui script atau event handler berbahaya
                    $svgDangerous = ['<script', 'javascript:', 'onload=', 'onerror=', 'onclick='];
                    foreach ($svgDangerous as $pattern) {
                        if (stripos($content, $pattern) !== false) {
                            $fail('Berkas SVG ditolak karena mengandung kode script yang tidak aman.');

                            return;
                        }
                    }
                }
            }
        }
    }
}
