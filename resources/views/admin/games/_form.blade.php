@php
    $hasImage = $game->exists && $game->image_url;
@endphp

<div class="grid gap-5 sm:grid-cols-2">
    <x-form.input
        name="name"
        id="game-name"
        label="Nama Game"
        :value="$game->name"
        required
        placeholder="Mobile Legends"
    />

    <x-form.input
        name="slug"
        id="game-slug"
        label="Slug URL"
        :value="$game->slug"
        placeholder="mobile-legends"
        data-slug-source="game-name"
        help="Kosongkan untuk dibuat otomatis dari nama game."
    />

    <div class="sm:col-span-2">
        @if ($hasImage)
            <div class="mb-3 flex items-center gap-4 rounded-lg border border-slate-200 p-3">
                <img
                    src="{{ $game->image_url }}"
                    alt=""
                    class="h-16 w-16 shrink-0 rounded-lg object-cover ring-1 ring-slate-200"
                />

                <p class="text-xs text-slate-500">
                    Gambar saat ini. Upload file baru untuk menggantinya, atau biarkan kosong untuk mempertahankan.
                </p>
            </div>
        @endif

        <x-form.file
            name="image"
            id="game-image"
            label="Gambar Game"
            :help="$hasImage ? null : 'Format JPG atau PNG, maksimal 2 MB.'"
        />
    </div>

    <div class="sm:col-span-2">
        <x-form.textarea
            name="description"
            label="Deskripsi"
            :value="$game->description"
            rows="4"
            placeholder="Catatan singkat tentang game ini."
        />
    </div>

    <x-form.select
        name="status"
        label="Status"
        :options="\App\Enums\GameStatus::options()"
        :value="$game->status?->value"
        required
        help="Game nonaktif tidak akan muncul di website publik."
    />
</div>
