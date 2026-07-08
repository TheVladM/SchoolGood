<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ReusablePhotoUpload
{
    /**
     * Upload a photo file and return the public URL.
     */
    protected function uploadPhoto(Request $request, string $inputName, Model $model, string $attribute, string $folder, string $disk = 'public'): ?string
    {
        if (! $request->hasFile($inputName)) {
            return null;
        }

        $this->deleteStoredPhoto($model, $attribute, $disk);

        $path = $request->file($inputName)->store($folder, $disk);

        return $path ? Storage::disk($disk)->url($path) : null;
    }

    /**
     * Delete a stored photo from the configured disk.
     */
    protected function deleteStoredPhoto(Model $model, string $attribute = 'photoURL', string $disk = 'public'): void
    {
        $value = $model->{$attribute} ?? '';

        if (empty($value)) {
            return;
        }

        $relativePath = $this->extractRelativeStoragePath($value, $disk);

        if ($relativePath && Storage::disk($disk)->exists($relativePath)) {
            Storage::disk($disk)->delete($relativePath);
        }
    }

    /**
     * Transform a public storage URL into a relative path for the disk.
     */
    protected function extractRelativeStoragePath(string $publicUrl, string $disk = 'public'): ?string
    {
        $diskUrl = rtrim(config('filesystems.disks.' . $disk . '.url', ''), '/');

        if ($diskUrl !== '' && Str::startsWith($publicUrl, $diskUrl)) {
            return ltrim(Str::after($publicUrl, $diskUrl), '/');
        }

        if (Str::startsWith($publicUrl, '/storage/')) {
            return ltrim(Str::after($publicUrl, '/storage/'), '/');
        }

        return $publicUrl;
    }
}
