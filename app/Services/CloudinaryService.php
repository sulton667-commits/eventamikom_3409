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
     * atau simpan ke direktori lokal public/uploads sebagai fallback.
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

        // 2. Fallback ke lokal storage jika Cloudinary tidak dikonfigurasi atau gagal
        if ($file instanceof UploadedFile) {
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path("uploads/{$folder}");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            return asset("uploads/{$folder}/{$filename}");
        }

        if (is_string($file) && file_exists($file)) {
            $filename = time() . '_' . basename($file);
            $destinationPath = public_path("uploads/{$folder}");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            copy($file, $destinationPath . '/' . $filename);
            return asset("uploads/{$folder}/{$filename}");
        }

        throw new \RuntimeException('Gagal mengunggah gambar: File tidak valid.');
    }

    /**
     * Hapus file dari Cloudinary berdasarkan public_id atau dari lokal.
     */
    public function delete(string $url): void
    {
        if (empty($url)) {
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
                $localPath = public_path(ltrim($relativePath, '/'));
                if (file_exists($localPath)) {
                    @unlink($localPath);
                }
            }
        } catch (\Throwable $e) {
            logger()->error('Delete file error: ' . $e->getMessage());
        }
    }
}