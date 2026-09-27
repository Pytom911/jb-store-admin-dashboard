<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GameStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGameRequest;
use App\Http\Requests\Admin\UpdateGameRequest;
use App\Models\Game;
use App\Services\GameImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(private readonly GameImageService $images) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $status = $request->string('status')->trim()->value();

        $games = Game::query()
            ->withStockCounts()
            ->when($search, fn ($query) => $query->where(
                fn ($query) => $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%"),
            ))
            ->when(in_array($status, GameStatus::values(), true), fn ($query) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.games.index', [
            'games' => $games,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.games.create', [
            'game' => new Game(['status' => GameStatus::Active->value]),
        ]);
    }

    public function store(StoreGameRequest $request): RedirectResponse
    {
        $game = Game::create([
            ...$request->safe()->except('image'),
            'image' => $request->file('image')
                ? $this->images->store($request->file('image'))
                : null,
        ]);

        return redirect()
            ->route('admin.games.index')
            ->with('success', "Game \"{$game->name}\" berhasil ditambahkan.");
    }

    public function edit(Game $game): View
    {
        return view('admin.games.edit', ['game' => $game]);
    }

    public function update(UpdateGameRequest $request, Game $game): RedirectResponse
    {
        $game->update([
            ...$request->safe()->except('image'),
            'image' => $this->images->replace($game->image, $request->file('image')),
        ]);

        return redirect()
            ->route('admin.games.index')
            ->with('success', "Game \"{$game->name}\" berhasil diperbarui.");
    }

    public function destroy(Game $game): RedirectResponse
    {
        if ($game->accounts()->exists()) {
            return back()->with(
                'error',
                "Game \"{$game->name}\" masih memiliki akun. Hapus atau pindahkan akunnya terlebih dahulu.",
            );
        }

        $this->images->delete($game->image);

        $game->delete();

        return redirect()
            ->route('admin.games.index')
            ->with('success', "Game \"{$game->name}\" berhasil dihapus.");
    }
}
