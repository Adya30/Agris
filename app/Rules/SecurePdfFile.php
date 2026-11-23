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

        // 4. Validasi Magic Bytes (File Signature PDF)
        // Standar PDF ISO 32000: bytes 0..4 WAJIB diawali '%PDF-' (0x25, 0x50, 0x44, 0x46, 0x2D)
        $path = $value->getRealPath();
        if (! $path || ! file_exists($path)) {
            $fail('Gagal mengakses file untuk validasi keamanan.');

            return;
        }

        $handle = @fopen($path, 'rb');
        if (! $handle) {
            $fail('Gagal membaca file untuk verifikasi struktur PDF.');

            return;
        }

        $header = fread($handle, 5);
        if ($header !== '%PDF-') {
            fclose($handle);
            $fail('Header file tidak sesuai dengan standar dokumen PDF resmi.');

            return;
        }

        // 5. Deep Content Inspection: Deteksi skrip terlarang / webshell / embedded code
        // Baca isi file untuk memeriksa pola injeksi skrip/payload
        $content = fread($handle, 1024 * 1024);
        fclose($handle);

        $dangerousPatterns = [
            '<?php', '<?=', '<script', '<%',
            '/Launch', '/EmbeddedFiles', '/JavaScript',
            'eval(', 'system(', 'shell_exec(', 'passthru(', 'exec(', 'base64_decode(',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                $fail('Dokumen ditolak karena mengandung kode, skrip, atau aksi otomatis yang tidak aman.');

                return;
            }
        }
    }
}
