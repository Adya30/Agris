<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\PotentiallyTranslatedString;

class SecurePdfFile implements ValidationRule
{
    protected int $maxSizeInKb;

    public function __construct(int $maxSizeInKb = 10240)
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
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('File dokumen tidak valid atau rusak.');

            return;
        }

        // 1. Validasi ukuran file (maksimal)
        if ($value->getSize() > $this->maxSizeInKb * 1024) {
            $fail("Ukuran dokumen MOU maksimal {$this->maxSizeInKb}KB.");

            return;
        }

        // 2. Validasi ekstensi nama file (case-insensitive)
        $clientExt = strtolower($value->getClientOriginalExtension());
        if ($clientExt !== 'pdf') {
            $fail('Format dokumen harus berupa file PDF.');

            return;
        }

        // Cegah double extension attack (misal: exploit.php.pdf)
        $clientName = strtolower($value->getClientOriginalName());
        $dangerousExts = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8', 'phar', 'sh', 'pl', 'py', 'cgi', 'asp', 'aspx', 'jsp', 'exe', 'bat', 'cmd', 'js', 'vbs', 'html', 'htm', 'svg'];
        $parts = explode('.', $clientName);
        if (count($parts) > 2) {
            foreach ($parts as $part) {
                if (in_array(trim($part), $dangerousExts, true)) {
                    $fail('Nama file dokumen mengandung ekstensi berbahaya atau tidak diizinkan.');

                    return;
                }
            }
        }

        // 3. Validasi Mime-Type dari PHP fileinfo
        $mime = $value->getMimeType();
        if ($mime !== 'application/pdf') {
            $fail('Format dokumen tidak valid. File harus benar-benar berformat PDF asli.');

            return;
        }

        // 4. Validasi Header PDF & Inspeksi Konten
        $path = $value->getRealPath();
        if (! $path || ! file_exists($path)) {
            $fail('Gagal mengakses file untuk validasi keamanan.');

            return;
        }

        $content = @file_get_contents($path);
        if ($content === false || ! str_contains(substr($content, 0, 1024), '%PDF-')) {
            $fail('Header file tidak sesuai dengan standar dokumen PDF resmi.');

            return;
        }

        // 5. Deep Content Inspection: Deteksi skrip terlarang / webshell PHP & eksekusi terlarang
        $dangerousPatterns = [
            '<?php', '<?=', '<script', '<%',
            '/Launch', '/JavaScript',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                $fail('Dokumen ditolak karena mengandung kode atau skrip terlarang.');

                return;
            }
        }
    }
}
