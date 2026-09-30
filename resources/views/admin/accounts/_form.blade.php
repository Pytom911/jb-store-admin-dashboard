@php
    $existingImages = $account->exists ? $account->images : collect();
    $remainingSlots = $account->remainingImageSlots();
@endphp

<div class="grid gap-5 sm:grid-cols-2">
    <x-form.select
        name="game_id"
        label="Game"
        :options="$games->pluck('name', 'id')->all()"
        :value="$account->game_id"
        required
        placeholder="Pilih game"
    />

    <x-form.input
        name="account_code"
        id="account-code"
        label="Kode Akun"
        :value="$account->account_code"
        required
        placeholder="ML-001"
        help="Kode unik, misal ML-001 atau PUB-042."
    />

    <div class="sm:col-span-2">
        <x-form.input
            name="title"
            label="Judul"
            :value="$account->title"
            required
            placeholder="Akun Sultan Rank Mythic 120 Skin"
        />
    </div>

    <x-form.input
        name="price"
        label="Harga"
        type="number"
        step="0.01"
        min="0"
        prefix="Rp"
        :value="$account->price"
        required
        placeholder="150000"
    />

    <x-form.select
        name="status"
        label="Status"
        :options="\App\Enums\AccountStatus::options()"
        :value="$account->status?->value"
        required
        help="Hanya akun tersedia yang tampil di website publik."
    />

    <div class="sm:col-span-2">
        <x-form.textarea
            name="description"
            label="Deskripsi"
            :value="$account->description"
            rows="4"
            placeholder="Detail tambahan, syarat, atau catatan penting."
        />
    </div>

    <div class="sm:col-span-2">
        <x-form.file
            name="images[]"
            id="account-images"
            label="Gambar Detail"
            :multiple="true"
            :help="$remainingSlots > 0
                ? "Pilih beberapa gambar sekaligus, maksimal {$remainingSlots} lagi. Format JPG, PNG, atau WEBP, maksimal 2 MB per file."
                : 'Kuota gambar sudah penuh. Hapus gambar lama dari halaman detail akun untuk menambah yang baru.'"
        />

        @if ($remainingSlots > 0 && $existingImages->isEmpty())
            <label class="mt-3 flex items-start gap-2.5 text-sm text-slate-600">
                <input
                    type="checkbox"
                    name="cover_first"
                    value="1"
                    class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20"
                />
                <span>Jadikan gambar pertama sebagai sampul akun ini.</span>
            </label>
        @elseif ($remainingSlots > 0)
            <p class="mt-3 text-xs text-slate-500">
                Sampul sudah ada, jadi gambar baru ini tidak akan menggantikannya. Ubah sampul dari halaman detail akun.
            </p>
        @endif
    </div>
</div>

@if ($existingImages->isNotEmpty())
    <div class="mt-6">
        <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">
            Gambar saat ini ({{ $existingImages->count() }})
        </p>

        <ul class="mt-3 flex flex-wrap gap-3">
            @foreach ($existingImages as $image)
                <li class="relative">
                    <img
                        src="{{ $image->url }}"
                        alt="Gambar detail {{ $account->account_code }}"
                        class="h-20 w-20 rounded-lg object-cover ring-1 ring-slate-200"
                    />

                    @if ($image->is_cover)
                        <span class="absolute -top-1.5 -left-1.5 rounded bg-indigo-600 px-1.5 py-0.5 text-[10px] font-semibold text-white">
                            Sampul
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
