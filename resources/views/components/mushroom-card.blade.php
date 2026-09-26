@props([
    'name',
    'scientificName',
    'summary',
    'imageUrl',
    'imageAlt',
    'wikipediaUrl',
    'creditUrl' => null,
    'creditLabel' => null,
])

<article {{ $attributes->merge(['class' => 'overflow-hidden rounded-3xl border border-mist-border bg-mist-surface shadow-lg dark:border-canopy-border dark:bg-canopy-surface']) }}>
    <div class="aspect-[4/3] overflow-hidden bg-canopy-surface">
        <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="size-full object-cover" loading="lazy" decoding="async">
    </div>

    <div class="space-y-3 p-6">
        <div>
            <h3 class="text-xl font-extrabold">{{ $name }}</h3>
            <p class="italic text-slate-subtle dark:text-mist-dark">{{ $scientificName }}</p>
        </div>

        <p class="text-sm leading-relaxed text-slate-muted dark:text-mist-dark">{{ $summary }}</p>

        <a href="{{ $wikipediaUrl }}" target="_blank" rel="noopener noreferrer" class="ada-focus inline-flex min-h-touch items-center rounded-xl bg-spore px-4 text-sm font-bold text-offwhite hover:bg-spore-hover">
            Explore on Wikipedia
            <span class="sr-only">: {{ $name }} (opens in a new tab)</span>
        </a>

        @if ($creditUrl && $creditLabel)
            <p class="border-t border-mist-border pt-3 text-xs text-slate-subtle dark:border-canopy-border dark:text-mist-dark">
                Image: <a href="{{ $creditUrl }}" target="_blank" rel="noopener noreferrer" class="ada-focus rounded underline underline-offset-2 hover:text-chanterelle">{{ $creditLabel }}<span class="sr-only"> (opens in a new tab)</span></a>
            </p>
        @endif
    </div>
</article>
