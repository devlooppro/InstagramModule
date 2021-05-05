<?php

namespace App\Instagram\Services;

use Storage;
use Str;

class DownloadService
{
    public static function download(?string $url, string $format): ?string
    {
        if (empty($url)) {
            return null;
        }

        $basePath = config('instagram.download_path');
        if (!Storage::exists($basePath)) {
            Storage::makeDirectory($basePath);
        }

        $fileName = Str::random(48) . '.' . $format;
        $path = $basePath . DIRECTORY_SEPARATOR . $fileName;
        copy($url, Storage::path($path));

        return $path;
    }
}
