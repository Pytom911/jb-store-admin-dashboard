<?php

namespace App\Http\Controllers\Public;

use App\Enums\AccountStatus;
use App\Enums\GameStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(): View
    {
        return view('public.games.index', [
            'games' => Game::query()
                ->active()
                ->withStockCounts()
                ->orderBy('name')
                ->paginate(12),
            'totalAvailable' => Account::query()->available()->count(),
        ]);
    }

    public function show(Request $request, Game $game): View
    {
        abort_unless($game->status === GameStatus::Active, 404);

        $status = $this->resolveStatus($request);
        $sort = $request->string('sort')->trim()->value();

        $game->loadCount(Game::stockCounts());

        $accounts = Account::query()
            ->where('game_id', $game->id)
            ->where('status', $status)
            ->with('coverImage')
            ->sorted($sort)
            ->paginate(config('marketplace.accounts_per_page'))
            ->withQueryString();

        return view('public.games.show', [
            'game' => $game,
            'accounts' => $accounts,
            'status' => $status,
            'sort' => $sort,
        ]);
    }

    private function resolveStatus(Request $request): string
    {
        $status = $request->string('status')->trim()->value();

        return in_array($status, AccountStatus::values(), true)
            ? $status
            : AccountStatus::Available->value;
    }
}
