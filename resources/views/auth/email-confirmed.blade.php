<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome to Mushroom Glades</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-mist font-sans text-slate-deep antialiased dark:bg-canopy dark:text-offwhite">
        <x-site-header active="profile" />
        <main class="min-h-screen px-4 py-10 pb-[calc(5rem+env(safe-area-inset-bottom))] sm:px-6 md:pb-10">
            <section class="mx-auto max-w-xl rounded-3xl border border-biolum/50 bg-mist-surface p-6 shadow-lg dark:border-biolum/40 dark:bg-canopy-surface sm:p-8">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-biolum-light">Email confirmed</p>
                <h1 class="mt-2 text-fluid-xl font-extrabold">Welcome, {{ $user->name }}.</h1>
                <p class="mt-3 text-slate-muted dark:text-mist-dark">Your Mushroom Glades account is ready. Your field journal will grow here soon.</p>
                <a href="{{ route('profile') }}" class="ada-focus mt-6 inline-flex min-h-touch items-center rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover">Go to Profile</a>
            </section>
        </main>
        <x-mobile-navigation-footer active="profile" />
    </body>
</html>
