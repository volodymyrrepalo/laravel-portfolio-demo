@props(['active' => null])

@php
    $items = [
        ['key' => 'home', 'label' => 'Home', 'href' => url('/')],
        ['key' => 'map', 'label' => 'Map', 'href' => route('map')],
        ['key' => 'favorites', 'label' => 'Favorites', 'href' => '#cards'],
        ['key' => 'profile', 'label' => 'Profile', 'href' => route('profile')],
    ];
@endphp

<footer {{ $attributes->merge(['class' => 'fixed inset-x-0 bottom-0 z-40 border-t border-mist-border bg-mist/95 px-3 pt-2 pb-[max(0.5rem,env(safe-area-inset-bottom))] shadow-[0_-8px_24px_rgb(15_30_23_/_12%)] backdrop-blur md:hidden dark:border-canopy-border dark:bg-canopy-surface/95 dark:shadow-[0_-8px_24px_rgb(0_0_0_/_35%)]']) }}>
    <nav aria-label="Mobile navigation" class="mx-auto grid max-w-md grid-cols-4 gap-1">
        @foreach ($items as $item)
            <a
                href="{{ $item['href'] }}"
                @if ($active === $item['key']) aria-current="page" @endif
                @class([
                    'ada-focus touch-target-inspect flex min-h-touch flex-col items-center justify-center gap-1 rounded-xl px-1 text-[0.6875rem] font-bold transition-colors',
                    'bg-chanterelle/15 text-chanterelle-dark dark:bg-chanterelle/20 dark:text-chanterelle-light' => $active === $item['key'],
                    'text-slate-muted hover:bg-mist-dark hover:text-spore dark:text-mist-dark dark:hover:bg-canopy-light dark:hover:text-offwhite' => $active !== $item['key'],
                ])
                @if (! in_array($item['key'], ['home', 'map', 'profile'], true)) data-demo-navigation-target title="Demo target: {{ $item['label'] }}" @endif
            >
                @switch($item['key'])
                    @case('home')
                        <svg aria-hidden="true" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10Z" />
                        </svg>
                        @break
                    @case('map')
                        <svg aria-hidden="true" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18-6 3V6l6-3 6 3 6-3v15l-6 3-6-3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v15m6-12v15" />
                        </svg>
                        @break
                    @case('favorites')
                        <svg aria-hidden="true" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.5 3.7 12.7A5.2 5.2 0 0 1 11 5.3L12 6.5l1-1.2a5.2 5.2 0 0 1 7.3 7.4L12 20.5Z" />
                        </svg>
                        @break
                    @case('profile')
                        <svg aria-hidden="true" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 21a8 8 0 0 1 16 0" />
                        </svg>
                        @break
                @endswitch
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</footer>
