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

    protected array $allowedMimes;

    public function __construct(int $maxSizeInKb = 5120, array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'jfif'])
    {
        $this->maxSizeInKb = $maxSizeInKb;
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
        $this->allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/pjpeg',
            'image/x-png',
        ];
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('File gambar tidak valid atau rusak.');

            return;
        }

        // 1. Validasi ukuran file
        if ($value->getSize() > $this->maxSizeInKb * 1024) {
            $fail("Ukuran gambar maksimal {$this->maxSizeInKb}KB.");

            return;
        }

        // 2. Validasi ekstensi nama file (case-insensitive)
        $clientExt = strtolower($value->getClientOriginalExtension());
        if (! in_array($clientExt, $this->allowedExtensions, true)) {
            $fail('Format gambar hanya diperbolehkan: '.strtoupper(implode(', ', $this->allowedExtensions)).'.');

            return;
        }

        // Cegah double extension attack (misal: image.php.png atau exploit.php.5.jpeg)
        $clientName = strtolower($value->getClientOriginalName());
        $dangerousExts = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8', 'phar', 'sh', 'pl', 'py', 'cgi', 'asp', 'aspx', 'jsp', 'exe', 'bat', 'cmd', 'js', 'vbs', 'html', 'htm', 'svg'];
        $parts = explode('.', $clientName);
        if (count($parts) > 2) {
            // Periksa segmen ekstensi tersembunyi sebelum ekstensi file terakhir
            $intermediateParts = array_slice($parts, 1, -1);
            foreach ($intermediateParts as $part) {
                if (in_array(trim($part), $dangerousExts, true)) {
                    $fail('Nama file gambar mengandung ekstensi ganda yang mencurigakan.');

                    return;
                }
            }
        }

        // 3. Validasi Mime-Type asli dari PHP Fileinfo
        $mime = $value->getMimeType();
        if (! in_array($mime, $this->allowedMimes, true)) {
            $fail('Tipe file gambar tidak valid atau tidak didukung.');

            return;
        }

        $path = $value->getRealPath();
        if (! $path || ! file_exists($path)) {
            $fail('Gagal mengakses file gambar untuk verifikasi keamanan.');

            return;
        }

        // 4. Deep Content Inspection: Deteksi Polyglot / WebShell Payload PHP & script injection di EXIF
        $content = @file_get_contents($path);
        if ($content !== false) {
            $dangerousPatterns = [
                '<?php', '<?=', '<script', '<%',
            ];

            foreach ($dangerousPatterns as $pattern) {
                if (stripos($content, $pattern) !== false) {
                    $fail('Gambar ditolak karena terdeteksi mengandung sisipan kode terlarang.');

                    return;
                }
            }
        }

        // 5. Verifikasi Integritas Gambar Asli via getimagesize()
        // Fungsi ini memeriksa header biner, dimensi (width/height), dan struktur gambar
        $imageInfo = @getimagesize($path);
        if (! $imageInfo || empty($imageInfo[0]) || empty($imageInfo[1])) {
            $fail('File terdeteksi bukan file gambar valid atau file mengalami kerusakan.');

            return;
        }

        $detectedMime = $imageInfo['mime'] ?? '';
        if (! in_array($detectedMime, $this->allowedMimes, true)) {
            $fail('Struktur internal file bukan format gambar yang valid.');

            return;
        }
    }
}
