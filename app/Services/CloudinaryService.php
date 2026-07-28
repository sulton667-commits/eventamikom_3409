<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CloudinaryService
{
    protected ?Cloudinary $cloudinary = null;

    protected function getCloudinaryUrl(): ?string
    {
        return config('services.cloudinary.url') ?? env('CLOUDINARY_URL');
    }

    protected function getCloudinary(): ?Cloudinary
    {
        if ($this->cloudinary === null) {
            $url = $this->getCloudinaryUrl();
            if ($url) {
                $this->cloudinary = new Cloudinary($url);
            }
        }

        return $this->cloudinary;
    }

    /**
     * Upload file ke Cloudinary jika CLOUDINARY_URL tersedia,
     * atau simpan ke direktori lokal / base64 data URI sebagai fallback.
     */
    public function upload(mixed $file, string $folder = 'posters'): string
    {
        $cloudinaryUrl = $this->getCloudinaryUrl();

        // 1. Coba upload ke Cloudinary jika dikonfigurasi
        if (!empty($cloudinaryUrl)) {
            try {
                $realPath = $file instanceof UploadedFile ? $file->getRealPath() : (string) $file;
                $cloudinary = $this->getCloudinary();
                if ($cloudinary) {
                    $result = $cloudinary->uploadApi()->upload($realPath, [
                        'folder' => $folder,
                    ]);

                    return $result['secure_url'];
                }
            } catch (\Throwable $e) {
                logger()->error('Cloudinary upload error: ' . $e->getMessage());
            }
        }

        // 2. Fallback ke lokal storage jika filesystem writable
        try {
            if ($file instanceof UploadedFile) {
                $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path("uploads/{$folder}");

                if (!is_dir($destinationPath)) {
                    @mkdir($destinationPath, 0755, true);
                }

                if (is_dir($destinationPath) && is_writable($destinationPath)) {
                    $file->move($destinationPath, $filename);
                    return asset("uploads/{$folder}/{$filename}");
                }
            } elseif (is_string($file) && file_exists($file)) {
                $filename = time() . '_' . basename($file);
                $destinationPath = public_path("uploads/{$folder}");

                if (!is_dir($destinationPath)) {
                    @mkdir($destinationPath, 0755, true);
                }

                if (is_dir($destinationPath) && is_writable($destinationPath)) {
                    copy($file, $destinationPath . '/' . $filename);
                    return asset("uploads/{$folder}/{$filename}");
                }
            }
        } catch (\Throwable $e) {
            logger()->warning('Local storage upload failed: ' . $e->getMessage());
        }

        // 3. Fallback terakhir jika filesystem Read-Only (Vercel): Convert ke Base64 Data URI
        if ($file instanceof UploadedFile) {
            $mime = $file->getMimeType() ?: 'image/jpeg';
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            return "data:{$mime};base64,{$base64}";
        }

        if (is_string($file) && file_exists($file)) {
            $mime = mime_content_type($file) ?: 'image/jpeg';
            $base64 = base64_encode(file_get_contents($file));
            return "data:{$mime};base64,{$base64}";
        }

        throw new \RuntimeException('Gagal mengunggah gambar: File tidak valid.');
    }

    /**
     * Hapus file dari Cloudinary berdasarkan public_id atau dari lokal.
     */
    public function delete(string $url): void
    {
        if (empty($url) || str_starts_with($url, 'data:')) {
            return;
        }

        try {
            if (str_contains($url, 'cloudinary.com')) {
                $cloudinaryUrl = $this->getCloudinaryUrl();
                if (!empty($cloudinaryUrl)) {
                    $path = parse_url($url, PHP_URL_PATH);
                    $parts = explode('/', trim($path, '/'));

                    // Buang bagian awal (cloud_name, image, upload, version)
                    $relevant = array_slice($parts, 4);
                    $publicIdWithExt = implode('/', $relevant);
                    $publicId = pathinfo($publicIdWithExt, PATHINFO_DIRNAME) . '/' . pathinfo($publicIdWithExt, PATHINFO_FILENAME);
                    $publicId = str_replace('./', '', $publicId);

                    $this->getCloudinary()?->uploadApi()->destroy($publicId);
                }
            } else {
                // Deletion for local fallback file
                $relativePath = parse_url($url, PHP_URL_PATH);
                if ($relativePath) {
                    $localPath = public_path(ltrim($relativePath, '/'));
                    if (file_exists($localPath)) {
                        @unlink($localPath);
                    }
                }
            }
        } catch (\Throwable $e) {
            logger()->error('Delete file error: ' . $e->getMessage());
        }
    }
}