<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Favorites · Mushroom Glades</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-mist font-sans text-slate-deep antialiased dark:bg-canopy dark:text-offwhite">
        <x-site-header active="favorites" />
        <main class="min-h-screen px-4 py-8 pb-[calc(5rem+env(safe-area-inset-bottom))] sm:px-6 md:pb-10">
            <section class="mx-auto max-w-6xl">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Field journal</p>
                <h1 class="mt-2 text-fluid-xl font-extrabold">Favorite finds</h1>
                <p class="mt-3 max-w-2xl text-slate-muted dark:text-mist-dark">Keep the locations you want to revisit close at hand.</p>

                <livewire:favorites.pin-list />
            </section>
        </main>
        <x-mobile-navigation-footer active="favorites" />
    </body>
</html>
