@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => null,
    'required' => false,
    'help' => null,
    'autocomplete' => 'new-password',
])

@php
    $id = $id ?? $name;
    $invalid = $errors->has($name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-fg">
            {{ $label }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
                <span class="sr-only">(wajib diisi)</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="password"
            value="{{ old($name, $value) }}"
            autocomplete="{{ $autocomplete }}"
            @if ($required)
                required
            @endif
            {{ $attributes->class([
                'block w-full rounded-xl border bg-field py-2.5 pr-11 pl-3 text-sm text-fg transition-colors',
                'focus-visible:outline-none focus-visible:ring-2',
                'border-danger focus-visible:border-danger focus-visible:ring-danger/25' => $invalid,
                'border-field-line focus-visible:border-brand focus-visible:ring-brand/30' => ! $invalid,
            ]) }}
        />

        <button
            type="button"
            data-reveal="{{ $id }}"
            aria-label="Lihat password"
            class="absolute inset-y-0 right-0 flex items-center rounded-r-xl px-3 text-fg-soft transition-colors hover:text-fg focus-visible:outline-none focus-visible:text-fg"
        >
            <svg data-eye class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <svg data-eye-slash class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243" />
            </svg>
        </button>
    </div>

    @if ($help && ! $invalid)
        <p class="mt-1.5 text-xs text-fg-soft">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs font-medium text-danger">{{ $message }}</p>
    @enderror
</div>