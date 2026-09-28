<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $intendedRoute === 'map' ? 'Map access' : 'Profile access' }} · Mushroom Glades</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-mist font-sans text-slate-deep antialiased dark:bg-canopy dark:text-offwhite">
        <x-site-header :active="$intendedRoute" />
        <main class="min-h-screen px-4 py-10 pb-[calc(5rem+env(safe-area-inset-bottom))] sm:px-6 md:pb-10">
            <livewire:auth.profile-access :intended-route="$intendedRoute" />
        </main>
        <x-mobile-navigation-footer :active="$intendedRoute" />
    </body>
</html>
