@props([
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionUrl' => null,
])

<div {{ $attributes->class('rounded-xl bg-white px-6 py-14 text-center ring-1 ring-slate-200') }}>
    <p class="text-sm font-semibold text-slate-900">{{ $title }}</p>

    @if ($description)
        <p class="mt-2 text-sm text-slate-500">{{ $description }}</p>
    @endif

    @if ($actionUrl && $actionLabel)
        <a
            href="{{ $actionUrl }}"
            class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500"
        >
            {{ $actionLabel }}
        </a>
    @endif
</div>
