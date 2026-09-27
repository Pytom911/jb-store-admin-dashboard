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
        name="username"
        label="Username"
        :value="$account->username"
        required
        autocomplete="off"
    />

    <x-form.password
        name="password"
        label="Password"
        :value="$account->password"
        required
        help="Disimpan terenkripsi di database. Jangan bagikan ke siapa pun selain pembeli."
    />

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
</div>
