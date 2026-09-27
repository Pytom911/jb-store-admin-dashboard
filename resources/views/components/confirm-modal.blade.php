<dialog
    data-confirm-modal
    aria-labelledby="confirm-modal-title"
    class="m-auto w-[calc(100%-2rem)] max-w-sm rounded-xl bg-white p-0 shadow-xl backdrop:bg-slate-900/50"
>
    <div class="p-6">
        <h2 id="confirm-modal-title" class="text-base font-semibold text-slate-900">Konfirmasi hapus</h2>

        <p data-confirm-message class="mt-2 text-sm text-slate-500"></p>

        <div class="mt-6 flex justify-end gap-3">
            <x-button type="button" variant="secondary" data-confirm-cancel>Batal</x-button>
            <x-button type="button" variant="danger" data-confirm-accept>Ya, hapus</x-button>
        </div>
    </div>

    <form data-confirm-form action="#" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</dialog>
