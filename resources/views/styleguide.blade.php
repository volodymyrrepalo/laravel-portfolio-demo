<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mushroom Glades Design System</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-mist font-sans text-slate-deep antialiased dark:bg-canopy dark:text-offwhite">
        <main data-styleguide class="min-h-screen pb-[calc(5rem+env(safe-area-inset-bottom))] md:pb-0">
            <div class="sr-only" aria-live="polite" data-announcement></div>

            <header class="sticky top-0 z-30 border-b border-mist-border/80 bg-mist/95 backdrop-blur dark:border-canopy-border dark:bg-canopy/95">
                <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
                    <a href="#top" class="ada-focus flex min-h-touch items-center gap-3 rounded-xl font-bold">
                        <span class="grid size-10 place-items-center rounded-xl bg-chanterelle text-xl text-canopy">🍄</span>
                        <span class="hidden sm:block">
                            <span class="block text-sm">Mushroom Glades</span>
                            <span class="block text-xs font-medium text-slate-subtle dark:text-mist-dark">Design system</span>
                        </span>
                    </a>

                    <div class="relative ml-auto w-full max-w-md">
                        <label class="sr-only" for="component-search">Search components</label>
                        <input id="component-search" data-search type="search" placeholder="Search components" class="ada-focus h-11 w-full rounded-xl border border-mist-border bg-mist-surface px-4 text-sm placeholder:text-slate-subtle dark:border-canopy-border dark:bg-canopy-surface dark:placeholder:text-mist-dark">
                    </div>

                    <button type="button" data-menu-button aria-label="Toggle section menu" aria-controls="mobile-nav-menu" aria-expanded="false" class="touch-target-inspect ada-focus grid size-touch shrink-0 place-items-center rounded-xl border border-mist-border text-lg dark:border-canopy-border md:hidden">☰</button>
                    <button type="button" data-touch-grid aria-pressed="false" class="touch-target-inspect ada-focus hidden min-h-touch rounded-xl border border-mist-border px-3 text-xs font-bold sm:block dark:border-canopy-border">48px grid</button>
                    <button type="button" data-theme-toggle aria-pressed="true" class="touch-target-inspect ada-focus min-h-touch shrink-0 rounded-xl bg-canopy-surface px-3 text-xs font-bold text-offwhite dark:bg-mist dark:text-canopy">Light mode</button>
                </div>
                <nav id="mobile-nav-menu" data-mobile-menu class="hidden border-t border-mist-border px-4 py-3 dark:border-canopy-border md:hidden" aria-label="Style guide sections">
                    <div class="mx-auto flex max-w-7xl flex-wrap gap-2 text-sm font-semibold">
                        <a class="ada-focus rounded-lg px-3 py-2 hover:text-chanterelle" href="#colors">Colors</a>
                        <a class="ada-focus rounded-lg px-3 py-2 hover:text-chanterelle" href="#typography">Type</a>
                        <a class="ada-focus rounded-lg px-3 py-2 hover:text-chanterelle" href="#controls">Controls</a>
                        <a class="ada-focus rounded-lg px-3 py-2 hover:text-chanterelle" href="#forms">Forms</a>
                    </div>
                </nav>
            </header>

            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8" id="top">
                <section class="mb-12 max-w-3xl space-y-4">
                    <p class="text-fluid-sm font-bold uppercase tracking-[0.2em] text-chanterelle">Portfolio foundation</p>
                    <h1 class="text-fluid-2xl font-black tracking-tight">A biophilic, mobile-first design language.</h1>
                    <p class="text-fluid-lg text-slate-muted dark:text-mist-dark">A Laravel-rendered reference for every visual and interactive element in the Mushroom Glades style guide.</p>
                </section>

                <div data-viewport-preview class="mx-auto transition-[max-width] duration-300">
                    <section id="colors" data-styleguide-section class="styleguide-section mb-16 space-y-6">
                        <div class="flex flex-wrap items-end justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold text-chanterelle">01 · Foundations</p>
                                <h2 class="text-fluid-xl font-extrabold">Color system and contrast</h2>
                            </div>
                            <div class="flex gap-2" aria-label="Preview width">
                                <button type="button" data-viewport data-viewport="375px" data-label="Mobile" class="ada-focus min-h-touch rounded-lg border border-mist-border px-3 text-xs font-bold dark:border-canopy-border">375</button>
                                <button type="button" data-viewport data-viewport="768px" data-label="Tablet" class="ada-focus min-h-touch rounded-lg border border-mist-border px-3 text-xs font-bold dark:border-canopy-border">768</button>
                                <button type="button" data-viewport data-viewport="none" data-label="Full width" class="ada-focus min-h-touch rounded-lg border border-mist-border px-3 text-xs font-bold dark:border-canopy-border">Full</button>
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <article class="overflow-hidden rounded-2xl border border-canopy-border bg-mist-surface shadow-sm dark:bg-canopy-surface"><div class="h-24 bg-canopy"></div><div class="flex items-center justify-between gap-2 p-4"><div><h3 class="font-bold">Canopy</h3><p class="text-xs text-slate-subtle dark:text-mist-dark">#0F1E17 · 15.8:1</p></div><button type="button" data-copy-value="#0F1E17" class="ada-focus min-h-touch rounded-lg px-2 text-xs font-bold text-chanterelle">Copy</button></div></article>
                            <article class="overflow-hidden rounded-2xl border border-canopy-border bg-mist-surface shadow-sm dark:bg-canopy-surface"><div class="h-24 bg-spore"></div><div class="flex items-center justify-between gap-2 p-4"><div><h3 class="font-bold">Spore</h3><p class="text-xs text-slate-subtle dark:text-mist-dark">#1B4D3E · 9.8:1</p></div><button type="button" data-copy-value="#1B4D3E" class="ada-focus min-h-touch rounded-lg px-2 text-xs font-bold text-chanterelle">Copy</button></div></article>
                            <article class="overflow-hidden rounded-2xl border border-canopy-border bg-mist-surface shadow-sm dark:bg-canopy-surface"><div class="h-24 bg-chanterelle"></div><div class="flex items-center justify-between gap-2 p-4"><div><h3 class="font-bold">Chanterelle</h3><p class="text-xs text-slate-subtle dark:text-mist-dark">#D97706 · 4.7:1</p></div><button type="button" data-copy-value="#D97706" class="ada-focus min-h-touch rounded-lg px-2 text-xs font-bold text-chanterelle">Copy</button></div></article>
                            <article class="overflow-hidden rounded-2xl border border-canopy-border bg-mist-surface shadow-sm dark:bg-canopy-surface"><div class="h-24 bg-biolum"></div><div class="flex items-center justify-between gap-2 p-4"><div><h3 class="font-bold">Biolum</h3><p class="text-xs text-slate-subtle dark:text-mist-dark">#0D9488 · 4.6:1</p></div><button type="button" data-copy-value="#0D9488" class="ada-focus min-h-touch rounded-lg px-2 text-xs font-bold text-chanterelle">Copy</button></div></article>
                            <article class="overflow-hidden rounded-2xl border border-canopy-border bg-mist-surface shadow-sm dark:bg-canopy-surface"><div class="h-24 bg-mist-dark"></div><div class="flex items-center justify-between gap-2 p-4"><div><h3 class="font-bold">Mist</h3><p class="text-xs text-slate-subtle dark:text-mist-dark">#F4F7F4 · surface</p></div><button type="button" data-copy-value="#F4F7F4" class="ada-focus min-h-touch rounded-lg px-2 text-xs font-bold text-chanterelle">Copy</button></div></article>
                            <article class="overflow-hidden rounded-2xl border border-canopy-border bg-mist-surface shadow-sm dark:bg-canopy-surface"><div class="h-24 bg-slate-deep"></div><div class="flex items-center justify-between gap-2 p-4"><div><h3 class="font-bold">Slate deep</h3><p class="text-xs text-slate-subtle dark:text-mist-dark">#1C2925 · text</p></div><button type="button" data-copy-value="#1C2925" class="ada-focus min-h-touch rounded-lg px-2 text-xs font-bold text-chanterelle">Copy</button></div></article>
                            <article class="overflow-hidden rounded-2xl border border-canopy-border bg-mist-surface shadow-sm dark:bg-canopy-surface"><div class="h-24 bg-slate-muted"></div><div class="flex items-center justify-between gap-2 p-4"><div><h3 class="font-bold">Slate muted</h3><p class="text-xs text-slate-subtle dark:text-mist-dark">#3D524A · secondary</p></div><button type="button" data-copy-value="#3D524A" class="ada-focus min-h-touch rounded-lg px-2 text-xs font-bold text-chanterelle">Copy</button></div></article>
                        </div>
                    </section>

                    <section id="typography" data-styleguide-section class="styleguide-section mb-16 rounded-3xl border border-mist-border bg-mist-surface p-6 dark:border-canopy-border dark:bg-canopy-surface sm:p-8">
                        <p class="text-sm font-bold text-chanterelle">02 · Typography</p>
                        <div class="mt-6 space-y-5">
                            <p class="text-fluid-2xl font-black leading-none">Mycelium connects every idea.</p>
                            <p class="text-fluid-xl font-extrabold">A fluid heading at every viewport.</p>
                            <p class="text-fluid-lg font-bold">A clear hierarchy makes the forest navigable.</p>
                            <p class="text-fluid-base leading-relaxed text-slate-muted dark:text-mist-dark">Body copy uses a comfortable rhythm and enough contrast to remain legible within dense interface specimens.</p>
                            <p class="text-fluid-sm font-semibold text-slate-subtle dark:text-mist-dark">Supporting text · medium weight · resilient at small widths</p>
                            <p class="text-fluid-xs uppercase tracking-[0.2em] text-slate-subtle dark:text-mist-dark">Caption / metadata / labels</p>
                        </div>
                    </section>

                    <section id="controls" data-styleguide-section class="styleguide-section mb-16 space-y-6">
                        <div><p class="text-sm font-bold text-chanterelle">03 · Actions</p><h2 class="text-fluid-xl font-extrabold">Buttons and interactive controls</h2></div>
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="space-y-4 rounded-3xl border border-mist-border bg-mist-surface p-6 dark:border-canopy-border dark:bg-canopy-surface">
                                <h3 class="font-bold">Button hierarchy</h3>
                                <div class="flex flex-wrap gap-3">
                                    <button type="button" class="touch-target-inspect ada-focus min-h-touch rounded-xl bg-chanterelle px-5 font-bold text-canopy shadow-amber-glow hover:bg-chanterelle-hover">Primary action</button>
                                    <button type="button" class="touch-target-inspect ada-focus min-h-touch rounded-xl bg-biolum px-5 font-bold text-white shadow-glow hover:bg-biolum-hover">Secondary action</button>
                                    <button type="button" class="touch-target-inspect ada-focus min-h-touch rounded-xl border-2 border-spore px-5 font-bold text-spore hover:bg-spore hover:text-offwhite dark:text-mist">Outlined action</button>
                                    <button type="button" disabled class="min-h-touch rounded-xl bg-mist-dark px-5 font-bold text-slate-subtle opacity-60 dark:bg-canopy-light">Disabled</button>
                                </div>
                            </div>
                            <div class="space-y-4 rounded-3xl border border-mist-border bg-mist-surface p-6 dark:border-canopy-border dark:bg-canopy-surface">
                                <h3 class="font-bold">Icon, choice and range controls</h3>
                                <div class="flex flex-wrap items-center gap-3">
                                    <button type="button" aria-label="Add specimen" class="touch-target-inspect ada-focus grid size-touch place-items-center rounded-xl bg-canopy text-xl text-chanterelle">+</button>
                                    <button type="button" aria-label="Save specimen" class="touch-target-inspect ada-focus grid size-touch place-items-center rounded-xl border border-mist-border text-xl dark:border-canopy-border">♡</button>
                                    <button type="button" role="switch" aria-checked="false" data-switch class="touch-target-inspect ada-focus flex h-7 w-12 items-center rounded-full bg-slate-subtle p-1 transition"><span class="size-5 rounded-full bg-white transition-transform"></span></button>
                                    <label class="flex min-h-touch items-center gap-2 text-sm font-semibold"><input type="checkbox" class="size-5 accent-chanterelle"> Save location</label>
                                    <label class="flex min-h-touch items-center gap-2 text-sm font-semibold"><input type="radio" name="growth" checked class="size-5 accent-biolum"> Active</label>
                                </div>
                                <label class="block text-sm font-semibold" for="humidity">Humidity preference <input id="humidity" type="range" min="0" max="100" value="65" class="mt-3 block w-full accent-chanterelle"></label>
                            </div>
                        </div>
                    </section>

                    <section id="forms" data-styleguide-section class="styleguide-section mb-16 space-y-6">
                        <div><p class="text-sm font-bold text-chanterelle">04 · Input</p><h2 class="text-fluid-xl font-extrabold">Forms and validation states</h2></div>
                        <form class="grid gap-5 rounded-3xl border border-mist-border bg-mist-surface p-6 dark:border-canopy-border dark:bg-canopy-surface md:grid-cols-2" action="#" onsubmit="return false">
                            <label class="space-y-2 text-sm font-bold">Specimen name <input type="text" value="Golden Chanterelle" class="ada-focus block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 font-normal dark:border-canopy-border dark:bg-canopy"></label>
                            <label class="space-y-2 text-sm font-bold">Habitat <select class="ada-focus block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 font-normal dark:border-canopy-border dark:bg-canopy"><option>Temperate forest</option><option>Old-growth woodland</option><option>Garden bed</option></select></label>
                            <label class="space-y-2 text-sm font-bold">Scientific name <input aria-invalid="true" type="text" value="Cantharellus" class="ada-focus block h-12 w-full rounded-xl border-2 border-chanterelle bg-mist px-3 font-normal dark:bg-canopy"><span class="block font-medium text-chanterelle">Enter a complete binomial name.</span></label>
                            <label class="space-y-2 text-sm font-bold">Collection reference <input aria-describedby="reference-success" type="text" value="MG-2409" class="ada-focus block h-12 w-full rounded-xl border-2 border-biolum bg-mist px-3 font-normal dark:bg-canopy"><span id="reference-success" class="block font-medium text-biolum-light">Reference is available.</span></label>
                            <label class="space-y-2 text-sm font-bold md:col-span-2">Field notes <textarea rows="4" class="ada-focus block w-full rounded-xl border border-mist-border bg-mist px-3 py-2 font-normal dark:border-canopy-border dark:bg-canopy">Found beneath beech and oak after rain.</textarea></label>
                        </form>
                    </section>

                    <section id="cards" data-styleguide-section class="styleguide-section mb-16 space-y-6">
                        <div><p class="text-sm font-bold text-chanterelle">05 · Surfaces</p><h2 class="text-fluid-xl font-extrabold">Cards and status banners</h2></div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div role="status" class="rounded-2xl border border-biolum/50 bg-biolum/10 p-4 text-biolum-light"><strong>Success:</strong> Observation has been saved.</div>
                            <div role="alert" class="rounded-2xl border border-chanterelle/50 bg-chanterelle/10 p-4 text-chanterelle-light"><strong>Attention:</strong> Review the field location before publishing.</div>
                        </div>
                        <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                            <article class="overflow-hidden rounded-3xl border border-mist-border bg-mist-surface shadow-lg dark:border-canopy-border dark:bg-canopy-surface">
                                <div class="flex h-36 items-end bg-gradient-to-br from-spore via-canopy-surface to-biolum p-6"><span class="rounded-full bg-mist/90 px-3 py-1 text-xs font-bold text-canopy">Fungal profile</span></div>
                                <div class="space-y-3 p-6"><div class="flex items-start justify-between gap-4"><div><h3 class="text-xl font-extrabold">Golden Chanterelle</h3><p class="italic text-slate-subtle dark:text-mist-dark">Cantharellus cibarius</p></div><span class="text-2xl">🍄</span></div><p class="text-sm leading-relaxed text-slate-muted dark:text-mist-dark">A bright, community-loved species that forms symbiotic relationships with hardwood trees.</p><button type="button" data-open-dialog class="ada-focus min-h-touch rounded-xl bg-spore px-4 text-sm font-bold text-offwhite hover:bg-spore-hover">View component code</button></div>
                            </article>
                            <aside class="grid grid-cols-2 gap-4 rounded-3xl border border-mist-border bg-mist-surface p-6 dark:border-canopy-border dark:bg-canopy-surface"><div><p class="text-3xl font-black text-chanterelle">48px</p><p class="text-sm font-semibold text-slate-subtle dark:text-mist-dark">minimum target</p></div><div><p class="text-3xl font-black text-biolum-light">AA</p><p class="text-sm font-semibold text-slate-subtle dark:text-mist-dark">contrast baseline</p></div><div class="col-span-2 border-t border-mist-border pt-4 dark:border-canopy-border"><p class="font-bold">Surface pattern</p><p class="mt-1 text-sm text-slate-muted dark:text-mist-dark">Rounded cards, subtle borders, and generous whitespace create a calm hierarchy.</p></div></aside>
                        </div>
                    </section>

                    <section id="navigation" data-styleguide-section class="styleguide-section mb-16 space-y-6">
                        <div><p class="text-sm font-bold text-chanterelle">06 · Navigation</p><h2 class="text-fluid-xl font-extrabold">Mobile layout and app bars</h2></div>
                        <div class="rounded-3xl border border-mist-border bg-mist-surface p-6 dark:border-canopy-border dark:bg-canopy-surface">
                            <nav aria-label="Breadcrumb" class="text-sm text-slate-subtle dark:text-mist-dark"><ol class="flex flex-wrap gap-2"><li><a class="ada-focus rounded hover:text-chanterelle" href="#top">Library</a></li><li>/</li><li><a class="ada-focus rounded hover:text-chanterelle" href="#cards">Species</a></li><li>/</li><li class="font-semibold text-slate-deep dark:text-offwhite" aria-current="page">Chanterelle</li></ol></nav>
                            <div class="mt-6 border-b border-mist-border dark:border-canopy-border" role="tablist" aria-label="Species details">
                                <button type="button" data-tab="overview" role="tab" aria-selected="true" class="ada-focus min-h-touch border-b-2 border-chanterelle px-4 font-bold text-chanterelle">Overview</button>
                                <button type="button" data-tab="habitat" role="tab" aria-selected="false" class="ada-focus min-h-touch border-b-2 border-transparent px-4 font-bold">Habitat</button>
                                <button type="button" data-tab="notes" role="tab" aria-selected="false" class="ada-focus min-h-touch border-b-2 border-transparent px-4 font-bold">Notes</button>
                            </div>
                            <div class="pt-5 text-sm leading-relaxed text-slate-muted dark:text-mist-dark"><div data-tab-panel="overview">Overview panel: a concise introduction to the species.</div><div data-tab-panel="habitat" class="hidden">Habitat panel: deciduous and mixed woodland.</div><div data-tab-panel="notes" class="hidden">Notes panel: keep discoveries private until verified.</div></div>
                            <nav aria-label="Mobile application navigation" class="mt-6 grid grid-cols-4 rounded-2xl bg-canopy p-2 text-center text-xs font-bold text-mist"><a class="ada-focus touch-target-inspect grid min-h-touch place-items-center rounded-xl bg-canopy-light text-chanterelle" href="#top">Home</a><a class="ada-focus touch-target-inspect grid min-h-touch place-items-center rounded-xl" href="#colors">Explore</a><a class="ada-focus touch-target-inspect grid min-h-touch place-items-center rounded-xl" href="#forms">Record</a><a class="ada-focus touch-target-inspect grid min-h-touch place-items-center rounded-xl" href="#cards">Profile</a></nav>
                        </div>
                    </section>

                    <section id="accessibility" data-styleguide-section class="styleguide-section mb-8 rounded-3xl border-2 border-chanterelle/60 bg-chanterelle/10 p-6 sm:p-8">
                        <p class="text-sm font-bold text-chanterelle-dark dark:text-chanterelle-light">07 · Accessibility</p>
                        <h2 class="mt-1 text-fluid-xl font-extrabold">Accessibility verification suite</h2>
                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl bg-mist-surface p-4 text-sm shadow-sm dark:bg-canopy-surface"><strong class="block text-biolum-light">Keyboard focus</strong><span class="mt-1 block text-slate-muted dark:text-mist-dark">Every interaction uses the visible amber focus treatment.</span></div>
                            <div class="rounded-2xl bg-mist-surface p-4 text-sm shadow-sm dark:bg-canopy-surface"><strong class="block text-biolum-light">Live feedback</strong><span class="mt-1 block text-slate-muted dark:text-mist-dark">Search, copy, and preference changes are announced.</span></div>
                            <div class="rounded-2xl bg-mist-surface p-4 text-sm shadow-sm dark:bg-canopy-surface"><strong class="block text-biolum-light">Touch targets</strong><span class="mt-1 block text-slate-muted dark:text-mist-dark">Use the 48px grid to inspect interactive areas.</span></div>
                        </div>
                    </section>
                </div>
            </div>

            <footer class="border-t border-mist-border px-4 py-8 text-center text-sm text-slate-subtle dark:border-canopy-border dark:text-mist-dark">Mushroom Glades Design System · Laravel + Tailwind CSS</footer>

            <dialog data-code-dialog class="w-[min(32rem,calc(100%-2rem))] rounded-3xl border border-mist-border bg-mist p-0 text-slate-deep shadow-2xl backdrop:bg-canopy/80 dark:border-canopy-border dark:bg-canopy-surface dark:text-offwhite">
                <div class="space-y-4 p-6"><div class="flex items-center justify-between gap-4"><h2 class="text-lg font-extrabold">Card component pattern</h2><button type="button" data-close-dialog class="ada-focus grid size-touch place-items-center rounded-xl border border-mist-border dark:border-canopy-border" aria-label="Close dialog">×</button></div><pre class="overflow-x-auto rounded-xl bg-canopy p-4 text-xs text-mist"><code>&lt;article class=&quot;rounded-3xl border p-6&quot;&gt;
    &lt;h3&gt;Golden Chanterelle&lt;/h3&gt;
&lt;/article&gt;</code></pre></div>
            </dialog>
        </main>
        <x-mobile-navigation-footer />
    </body>
</html>
