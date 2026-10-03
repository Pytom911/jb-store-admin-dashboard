@if (session('success'))
    <div
        data-flash
        role="status"
        class="mb-5 flex items-start gap-3 rounded-xl bg-pop-lime/10 px-4 py-3.5 text-sm font-medium text-pop-lime ring-1 ring-inset ring-pop-lime/25"
    >
        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
        <p class="break-words">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div
        data-flash
        role="alert"
        class="mb-5 flex items-start gap-3 rounded-xl bg-red-50 px-4 py-3.5 text-sm font-medium text-red-700 ring-1 ring-inset ring-red-600/25"
    >
        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z" />
        </svg>
        <p class="break-words">{{ session('error') }}</p>
    </div>
@endif