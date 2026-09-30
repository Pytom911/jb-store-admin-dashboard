<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Game;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('public.home', [
            'stats' => [
                'games' => Game::query()->active()->count(),
                'accounts' => Account::query()->count(),
                'available' => Account::query()->available()->count(),
                'sold' => Account::query()->sold()->count(),
            ],
            'games' => Game::query()
                ->active()
                ->withStockCounts()
                ->orderBy('name')
                ->limit(6)
                ->get(),
            'latestAccounts' => Account::query()
                ->with(['game:id,name,slug,image', 'coverImage'])
                ->latest()
                ->limit(8)
                ->get(),
            'availableAccounts' => Account::query()
                ->available()
                ->with(['game:id,name,slug,image', 'coverImage'])
                ->sorted('price_asc')
                ->limit(8)
                ->get(),
        ]);
    }
}
