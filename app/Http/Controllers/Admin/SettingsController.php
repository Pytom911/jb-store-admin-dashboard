<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', [
            'user' => auth()->user(),
            'whatsappNumber' => config('marketplace.whatsapp_number'),
            'environment' => config('app.env'),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->safe()->only(['name', 'email']));

        if ($request->filled('password')) {
            $user->password = $request->string('password')->value();
        }

        $user->save();

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
