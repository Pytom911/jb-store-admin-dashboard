<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GameImageService
{
    private const DISK = 'public';

    private const DIRECTORY = 'games';

    public function store(UploadedFile $image): string
    {
        return $image->store(self::DIRECTORY, self::DISK);
    }

    public function replace(?string $current, ?UploadedFile $image): ?string
    {
        if (! $image) {
            return $current;
        }

        $this->delete($current);

        return $this->store($image);
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}
