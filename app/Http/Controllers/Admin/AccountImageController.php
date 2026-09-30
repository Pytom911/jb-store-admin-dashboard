<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccountImageRequest;
use App\Models\Account;
use App\Models\AccountImage;
use App\Services\AccountImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountImageController extends Controller
{
    public function __construct(private readonly AccountImageService $images) {}

    public function store(StoreAccountImageRequest $request, Account $account): RedirectResponse
    {
        $this->images->storeMany($account, $request->file('images', []), $request->boolean('cover_first'));

        return back()->with('success', "Gambar berhasil ditambahkan ke akun {$account->account_code}.");
    }

    public function update(Request $request, Account $account, AccountImage $image): RedirectResponse
    {
        abort_unless($image->account_id === $account->id, 404);

        $this->images->setCover($account, $image);

        return back()->with('success', "Gambar sekarang menjadi sampul akun {$account->account_code}.");
    }

    public function destroy(Account $account, AccountImage $image): RedirectResponse
    {
        abort_unless($image->account_id === $account->id, 404);

        $this->images->delete($image);

        return back()->with('success', "Gambar berhasil dihapus dari akun {$account->account_code}.");
    }
}
