@props(['active' => null])

@php
    $items = [
        ['key' => 'home', 'label' => 'Home', 'href' => url('/')],
        ['key' => 'map', 'label' => 'Map', 'href' => '#navigation'],
        ['key' => 'favorites', 'label' => 'Favorites', 'href' => '#cards'],
        ['key' => 'profile', 'label' => 'Profile', 'href' => '#accessibility'],
    ];
@endphp

<div data-site-header>
    <header {{ $attributes->merge(['class' => 'sticky top-0 z-30 border-b border-mist-border/80 bg-mist/95 backdrop-blur dark:border-canopy-border dark:bg-canopy/95']) }}>
    <div class="mx-auto flex justify-between max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="ada-focus flex min-h-touch items-center gap-3 rounded-xl font-bold">
            <span class="grid size-10 place-items-center rounded-xl bg-chanterelle text-xl text-canopy">🍄</span>
            <span>
                <span class="block text-sm dark:text-mist-dark">Mushroom Glades</span>
                <span class="block text-xs font-medium text-slate-subtle dark:text-mist-dark">Field journal</span>
            </span>
        </a>

        <nav aria-label="Primary navigation" class="ml-auto hidden items-center gap-1 md:flex">
            @foreach ($items as $item)
                <a
                    href="{{ $item['href'] }}"
                    @if ($active === $item['key']) aria-current="page" @endif
                    @class([
                        'py-3 ada-focus min-h-touch rounded-xl px-3 text-sm font-bold transition-colors',
                        'bg-chanterelle/15 text-chanterelle-dark dark:bg-chanterelle/20 dark:text-chanterelle-light' => $active === $item['key'],
                        'text-slate-muted hover:bg-mist-dark hover:text-spore dark:text-mist-dark dark:hover:bg-canopy-light dark:hover:text-offwhite' => $active !== $item['key'],
                    ])
                    @if ($item['key'] !== 'home') data-demo-navigation-target title="Demo target: {{ $item['label'] }}" @endif
                >{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <button type="button" data-theme-toggle aria-pressed="true" class="ada-focus ml-0 min-h-touch shrink-0 rounded-xl bg-canopy-surface px-3 text-xs font-bold text-offwhite dark:ml-0 dark:bg-mist dark:text-canopy">Light mode</button>
        <button type="button" data-menu-button aria-label="Open navigation" aria-controls="mobile-navigation-menu" aria-expanded="false" class="touch-target-inspect ada-focus grid size-touch shrink-0 place-items-center rounded-xl border border-mist-border text-lg dark:border-canopy-border dark:text-mist-dark md:hidden">☰</button>
    </div>
    </header>

    <nav id="mobile-navigation-menu" data-mobile-menu class="fixed inset-0 z-50 hidden min-h-dvh bg-canopy px-6 text-offwhite md:hidden" aria-label="Mobile navigation">
        <button type="button" data-menu-close aria-label="Close navigation" class="ada-focus absolute top-4 right-4 grid size-touch place-items-center rounded-xl border border-canopy-border text-2xl text-chanterelle">×</button>
        <div class="flex min-h-dvh flex-col items-center justify-center gap-5 text-center">
            @foreach ($items as $item)
                <a
                    href="{{ $item['href'] }}"
                    @if ($active === $item['key']) aria-current="page" @endif
                    @class([
                        'ada-focus min-h-touch rounded-xl px-6 py-3 text-2xl font-black transition-colors',
                        'bg-chanterelle/20 text-chanterelle-light' => $active === $item['key'],
                        'text-mist hover:bg-canopy-light hover:text-chanterelle' => $active !== $item['key'],
                    ])
                    @if ($item['key'] !== 'home') data-demo-navigation-target title="Demo target: {{ $item['label'] }}" @endif
                >{{ $item['label'] }}</a>
            @endforeach
        </div>
    </nav>
</div>
