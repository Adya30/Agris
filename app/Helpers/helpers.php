<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('storage_url')) {
    /**
     * Get the public URL for a file stored on the default or specified filesystem disk.
     * Compatible with local storage ('public') and object storage ('s3' / Cloudflare R2 / AWS S3).
     */
    function storage_url(?string $path, ?string $disk = null): ?string
    {
        if (blank($path)) {
            return null;
        }

        // If path is already an external absolute URL or data URI, return directly
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//') || str_starts_with($path, 'data:')) {
            return $path;
        }

        // Clean leading slashes and strip redundant 'storage/' prefix
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        $targetDisk = $disk ?: config('filesystems.default', 'public');

        if ($targetDisk === 'public') {
            return asset('storage/'.$cleanPath);
        }

        try {
            return Storage::disk($targetDisk)->url($cleanPath);
        } catch (Throwable $e) {
            return asset('storage/'.$cleanPath);
        }
    }
}
