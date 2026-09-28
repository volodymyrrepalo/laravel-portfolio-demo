@props(['pin'])

<article {{ $attributes->merge(['class' => 'flex h-full flex-col rounded-3xl border border-mist-border bg-mist-surface p-5 shadow-lg dark:border-canopy-border dark:bg-canopy-surface']) }}>
    <div class="flex items-start gap-3">
        <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-chanterelle/15 text-2xl" aria-hidden="true">📍</span>
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Saved location</p>
            <h2 class="mt-1 text-xl font-extrabold">{{ $pin->comment ?: 'No comment recorded' }}</h2>
        </div>
    </div>

    <dl class="mt-5 grid grid-cols-2 gap-3 rounded-2xl bg-mist p-3 text-sm dark:bg-canopy">
        <div>
            <dt class="font-semibold text-slate-muted dark:text-mist-dark">Latitude</dt>
            <dd class="mt-1 font-bold">{{ number_format($pin->latitude, 5) }}</dd>
        </div>
        <div>
            <dt class="font-semibold text-slate-muted dark:text-mist-dark">Longitude</dt>
            <dd class="mt-1 font-bold">{{ number_format($pin->longitude, 5) }}</dd>
        </div>
    </dl>

    <div class="mt-5 flex flex-wrap gap-3">
        <a href="{{ route('map', ['pin' => $pin]) }}" class="ada-focus inline-flex min-h-touch items-center justify-center rounded-xl bg-chanterelle px-4 font-bold text-canopy hover:bg-chanterelle-hover">View on map</a>
        {{ $slot }}
    </div>
</article>
