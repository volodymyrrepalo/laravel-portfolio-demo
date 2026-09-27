@props([
    'mushrooms' => [],
    'label' => 'Mushroom highlights',
])

@if (count($mushrooms))
    <section {{ $attributes->merge(['class' => 'ada-focus rounded-3xl border border-mist-border bg-mist-surface p-4 shadow-lg dark:border-canopy-border dark:bg-canopy-surface sm:p-6']) }} data-mushroom-carousel tabindex="0" role="region" aria-roledescription="carousel" aria-label="{{ $label }}">
        <div class="mx-auto max-w-md">
            <div class="mb-4 flex items-center justify-between gap-4">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-chanterelle">Species spotlight</p>
                <p class="text-sm font-semibold text-slate-subtle dark:text-mist-dark" aria-live="polite" aria-atomic="true" data-carousel-status>Slide 1 of {{ count($mushrooms) }}</p>
            </div>

            <div class="relative">
                @foreach ($mushrooms as $index => $mushroom)
                    <div data-carousel-slide data-carousel-name="{{ $mushroom['name'] }}" role="group" aria-roledescription="slide" aria-label="{{ $index + 1 }} of {{ count($mushrooms) }}: {{ $mushroom['name'] }}" @if ($index > 0) class="hidden" aria-hidden="true" @endif>
                        <x-mushroom-card
                            :name="$mushroom['name']"
                            :scientific-name="$mushroom['scientificName']"
                            :summary="$mushroom['summary']"
                            :image-url="$mushroom['imageUrl']"
                            :image-alt="$mushroom['imageAlt']"
                            :wikipedia-url="$mushroom['wikipediaUrl']"
                            :credit-url="$mushroom['creditUrl'] ?? null"
                            :credit-label="$mushroom['creditLabel'] ?? null"
                        />
                    </div>
                @endforeach
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <button type="button" data-carousel-previous class="ada-focus min-h-touch rounded-xl border border-mist-border px-4 text-sm font-bold hover:bg-mist-dark disabled:cursor-not-allowed disabled:opacity-50 dark:text-mist-dark dark:border-canopy-border dark:hover:bg-canopy-light">Previous mushroom</button>
                <button type="button" data-carousel-next class="ada-focus min-h-touch rounded-xl bg-chanterelle px-4 text-sm font-bold text-canopy hover:bg-chanterelle-hover disabled:cursor-not-allowed disabled:opacity-50">Next mushroom</button>
            </div>
        </div>
    </section>
@endif
