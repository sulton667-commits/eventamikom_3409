<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected ?Cloudinary $cloudinary = null;

    protected function getCloudinary(): Cloudinary
    {
        if ($this->cloudinary === null) {
            $url = config('services.cloudinary.url') ?? env('CLOUDINARY_URL');
            if (empty($url)) {
                throw new \RuntimeException('CLOUDINARY_URL is missing or empty in environment configuration.');
            }
            $this->cloudinary = new Cloudinary($url);
        }

        return $this->cloudinary;
    }

    /**
     * Upload file ke Cloudinary, return secure URL-nya.
     */
    public function upload(string $filePath, string $folder = 'posters'): string
    {
        $result = $this->getCloudinary()->uploadApi()->upload($filePath, [
            'folder' => $folder,
        ]);

        return $result['secure_url'];
    }

    /**
     * Hapus file dari Cloudinary berdasarkan public_id.
     * public_id diambil dari URL yang tersimpan di database.
     */
    public function delete(string $url): void
    {
        // Ambil public_id dari url, contoh:
        // https://res.cloudinary.com/xxx/image/upload/v123456/posters/abc123.jpg
        // public_id-nya = posters/abc123
        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));

        // Buang bagian awal (cloud_name, image, upload, version)
        $relevant = array_slice($parts, 4);
        $publicIdWithExt = implode('/', $relevant);
        $publicId = pathinfo($publicIdWithExt, PATHINFO_DIRNAME) . '/' . pathinfo($publicIdWithExt, PATHINFO_FILENAME);
        $publicId = str_replace('./', '', $publicId);

        $this->getCloudinary()->uploadApi()->destroy($publicId);
    }
}