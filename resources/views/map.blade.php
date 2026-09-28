<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Map · Mushroom Glades</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-mist font-sans text-slate-deep antialiased dark:bg-canopy dark:text-offwhite">
        <x-site-header active="map" />
        <main class="min-h-screen px-4 py-8 pb-[calc(5rem+env(safe-area-inset-bottom))] sm:px-6 md:pb-10">
            <section class="mx-auto max-w-6xl">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Field journal</p>
                <h1 class="mt-2 text-fluid-xl font-extrabold">Your mushroom map</h1>
                <p class="mt-3 max-w-2xl text-slate-muted dark:text-mist-dark">Mark the places you have explored and keep a private note with each find.</p>

                <livewire:map.pin-manager />
            </section>
        </main>
        <x-mobile-navigation-footer active="map" />
    </body>
</html>
