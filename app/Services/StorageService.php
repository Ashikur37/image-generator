<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    public static function storeFile($filename,$file): string
    {
        $success = Storage::put($filename, $file);

        if (!$success) {
            throw new Exception('Failed to upload file');
        }

        return Storage::url($filename);
    }

    public static function deleteFile($filename): void
    {
        if ($filename && Storage::exists($filename)) {
            Storage::delete($filename);
        }
    }
}
