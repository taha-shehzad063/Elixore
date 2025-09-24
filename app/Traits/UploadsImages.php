<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait UploadsImages
{
    /**
     * Upload image directly to /public/admin/{folder}
     */
    public function uploadImage($file, $folder)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension; // unique name
        $filePath = "admin/{$folder}/" . $filename;

        // Move file to public/admin/{folder}
        $file->move(public_path("admin/{$folder}"), $filename);

        return $filePath; // relative path
    }

    /**
     * Update image: delete old and upload new to /public/admin/{folder}
     */
    public function updateImage(?string $oldPath, UploadedFile $newFile, string $folder): string
    {
        // Delete old file if exists
        if ($oldPath) {
            $oldFullPath = public_path($oldPath);

            if (file_exists($oldFullPath)) {
                @unlink($oldFullPath);
            }
        }

        $extension = $newFile->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        $filePath = "admin/{$folder}/" . $filename;

        // Move new file
        $newFile->move(public_path("admin/{$folder}"), $filename);

        return $filePath;
    }

    /**
     * Delete image from /public
     */
    public function deleteImage(?string $path): void
    {
        if ($path) {
            $fullPath = public_path($path);

            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
