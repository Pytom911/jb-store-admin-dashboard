<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Owns the files behind AccountImage rows and keeps the cover invariant: as long as
 * an account has at least one image, exactly one of them is flagged as the cover.
 * Views can therefore treat coverImage() as either present or legitimately absent
 * because the account has no images at all, and never in between.
 */
class AccountImageService
{
    private const DISK = 'public';

    private const DIRECTORY = 'accounts';

    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function storeMany(Account $account, array $files, bool $wantsCover): void
    {
        $files = array_values($files);

        if ($files === []) {
            return;
        }

        $coverPending = ! $account->coverImage()->exists();
        $coverAssigned = false;

        foreach (array_values($files) as $index => $file) {
            $isCover = $coverPending && $wantsCover && $index === 0;

            $account->images()->create([
                'path' => $file->store(self::DIRECTORY, self::DISK),
                'is_cover' => $isCover,
            ]);

            $coverAssigned = $coverAssigned || $isCover;
        }

        // An account is never left without a cover, so an unticked checkbox still
        // hands the flag to whichever image was stored first.
        if ($coverPending && ! $coverAssigned) {
            $this->promoteOldest($account);
        }
    }

    public function delete(AccountImage $image): void
    {
        $account = $image->account;
        $wasCover = $image->is_cover;

        $this->discardFile($image->path);

        $image->delete();

        if ($wasCover) {
            $this->promoteOldest($account);
        }
    }

    public function setCover(Account $account, AccountImage $image): void
    {
        $account->images()->where('id', '!=', $image->id)->update(['is_cover' => false]);

        $image->update(['is_cover' => true]);
    }

    public function deleteAll(Account $account): void
    {
        foreach ($account->images as $image) {
            $this->discardFile($image->path);
        }

        $account->images()->delete();
    }

    /**
     * Hands the cover to the oldest remaining image, which keeps the admin gallery
     * and the public listing showing the same picture after a cover is deleted.
     */
    private function promoteOldest(Account $account): void
    {
        $account->images()
            ->reorder('id')
            ->first()
            ?->update(['is_cover' => true]);
    }

    private function discardFile(?string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}
