<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageWebpService
{
    protected ?ImageManager $manager = null;

    public function __construct()
    {
        try {
            $this->manager = new ImageManager(new Driver);
        } catch (\Throwable $e) {
            Log::warning('Intervention Image Driver initialization fallback: '.$e->getMessage());
        }
    }

    /**
     * Convert and store an uploaded image file into WebP format.
     *
     * @param  string  $destinationSubdir  Subdirectory under public/uploads (e.g. 'products', 'projects')
     * @param  int  $quality  WebP compression quality (1-100)
     * @param  int|null  $maxWidth  Optional downscaling width for high DPI screens
     * @return string Relative URL path starting with /uploads/...
     */
    public function convertToWebp(
        UploadedFile|string $file,
        string $destinationSubdir = 'products',
        int $quality = 85,
        ?int $maxWidth = 1600
    ): string {
        $targetDir = public_path('uploads/'.trim($destinationSubdir, '/'));
        if (! File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true, true);
        }

        $filename = Str::uuid()->toString().'.webp';
        $targetPath = $targetDir.DIRECTORY_SEPARATOR.$filename;
        $publicUrl = '/uploads/'.trim($destinationSubdir, '/').'/'.$filename;

        $sourcePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        try {
            if ($this->manager) {
                $image = $this->manager->decodePath($sourcePath);

                if ($maxWidth && $image->width() > $maxWidth) {
                    $image->scaleDown(width: $maxWidth);
                }

                $image->save($targetPath, quality: $quality);

                return $publicUrl;
            }
        } catch (\Throwable $e) {
            Log::warning('Intervention Image conversion failed, falling back to GD: '.$e->getMessage());
        }

        // Native GD Fallback
        return $this->gdFallbackConvert($sourcePath, $targetPath, $publicUrl, $quality, $maxWidth);
    }

    /**
     * Fallback conversion using native PHP GD
     */
    protected function gdFallbackConvert(
        string $sourcePath,
        string $targetPath,
        string $publicUrl,
        int $quality,
        ?int $maxWidth
    ): string {
        $contents = file_get_contents($sourcePath);
        $gdImg = @imagecreatefromstring($contents);

        if (! $gdImg) {
            // If cannot decode, simply copy file
            copy($sourcePath, $targetPath);

            return $publicUrl;
        }

        $origWidth = imagesx($gdImg);
        $origHeight = imagesy($gdImg);

        if ($maxWidth && $origWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round(($origHeight / $origWidth) * $newWidth);
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $gdImg, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($gdImg);
            $gdImg = $resized;
        }

        imagepalettetotruecolor($gdImg);
        imagealphablending($gdImg, true);
        imagesavealpha($gdImg, true);
        imagewebp($gdImg, $targetPath, $quality);
        imagedestroy($gdImg);

        return $publicUrl;
    }
}
