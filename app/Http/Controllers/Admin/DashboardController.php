<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Game;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalGames' => Game::query()->count(),
            'totalAccounts' => Account::query()->count(),
            'availableAccounts' => Account::query()->available()->count(),
            'soldAccounts' => Account::query()->sold()->count(),
            'recentAccounts' => Account::query()
                ->with('game:id,name,slug,image')
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
