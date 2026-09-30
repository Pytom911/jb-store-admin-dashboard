<?php

namespace App\Http\Controllers\Public;

use App\Enums\AccountStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $gameSlug = $request->string('game')->trim()->value();
        $status = $request->string('status')->trim()->value();
        $sort = $request->string('sort')->trim()->value();

        $accounts = Account::query()
            ->with(['game:id,name,slug,image', 'coverImage'])
            ->search($search)
            ->where('status', in_array($status, AccountStatus::values(), true)
                ? $status
                : AccountStatus::Available->value)
            ->when($gameSlug, fn ($query) => $query->whereHas(
                'game',
                fn ($game) => $game->where('slug', $gameSlug),
            ))
            ->sorted($sort)
            ->paginate(config('marketplace.accounts_per_page'))
            ->withQueryString();

        return view('public.accounts.index', [
            'accounts' => $accounts,
            'games' => Game::query()
                ->active()
                ->withStockCounts()
                ->orderBy('name')
                ->get(),
            'search' => $search,
            'gameSlug' => $gameSlug,
            'status' => $status,
            'sort' => $sort,
        ]);
    }

    public function show(string $account_code): View
    {
        $account = Account::query()
            ->with(['game:id,name,slug,image', 'images'])
            ->where('account_code', $account_code)
            ->firstOrFail();

        return view('public.accounts.show', ['account' => $account]);
    }
}
