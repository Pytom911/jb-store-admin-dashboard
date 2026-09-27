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
            'games' => Game::query()
                ->active()
                ->withStockCounts()
                ->orderBy('name')
                ->limit(6)
                ->get(),
            'latestAccounts' => Account::query()
                ->withoutCredentials()
                ->with('game:id,name,slug,image')
                ->latest()
                ->limit(8)
                ->get(),
            'availableAccounts' => Account::query()
                ->withoutCredentials()
                ->available()
                ->with('game:id,name,slug,image')
                ->sorted('price_asc')
                ->limit(8)
                ->get(),
        ]);
    }
}
