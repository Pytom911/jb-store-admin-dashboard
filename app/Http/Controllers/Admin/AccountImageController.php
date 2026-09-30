<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountImage;
use App\Services\AccountImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountImageController extends Controller
{
    public function __construct(private readonly AccountImageService $images) {}

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
