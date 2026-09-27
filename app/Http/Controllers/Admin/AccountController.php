<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccountRequest;
use App\Http\Requests\Admin\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $status = $request->string('status')->trim()->value();
        $gameId = $request->integer('game_id');
        $sort = in_array($request->string('sort')->trim()->value(), ['price_asc', 'price_desc'], true)
            ? $request->string('sort')->trim()->value()
            : null;

        $accounts = Account::query()
            ->with('game:id,name,slug')
            ->search($search)
            ->when(in_array($status, AccountStatus::values(), true), fn ($query) => $query->where('status', $status))
            ->when($gameId, fn ($query) => $query->where('game_id', $gameId))
            ->sorted($sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.accounts.index', [
            'accounts' => $accounts,
            'games' => $this->gamesForSelect(),
            'search' => $search,
            'status' => $status,
            'gameId' => $gameId,
            'sort' => $sort,
        ]);
    }

    public function create(): View
    {
        return view('admin.accounts.create', [
            'account' => new Account(['status' => AccountStatus::Available->value]),
            'games' => $this->gamesForSelect(),
        ]);
    }

    public function store(StoreAccountRequest $request): RedirectResponse
    {
        $account = Account::create($request->validated());

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', "Akun {$account->account_code} berhasil ditambahkan.");
    }

    public function show(Account $account): View
    {
        return view('admin.accounts.show', [
            'account' => $account->load('game'),
        ]);
    }

    public function edit(Account $account): View
    {
        return view('admin.accounts.edit', [
            'account' => $account->load('game'),
            'games' => $this->gamesForSelect(),
        ]);
    }

    public function update(UpdateAccountRequest $request, Account $account): RedirectResponse
    {
        $account->update($request->validated());

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', "Akun {$account->account_code} berhasil diperbarui.");
    }

    public function destroy(Account $account): RedirectResponse
    {
        $code = $account->account_code;

        $account->delete();

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', "Akun {$code} berhasil dihapus.");
    }

    /**
     * @return Collection<int, Game>
     */
    private function gamesForSelect(): Collection
    {
        return Game::query()->orderBy('name')->get(['id', 'name']);
    }
}
