<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verify your email · Mushroom Glades</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-mist font-sans text-slate-deep antialiased dark:bg-canopy dark:text-offwhite">
        <x-site-header active="profile" />
        <main class="min-h-screen px-4 py-10 pb-[calc(5rem+env(safe-area-inset-bottom))] sm:px-6 md:pb-10">
            <section class="mx-auto max-w-xl rounded-3xl border border-mist-border bg-mist-surface p-6 shadow-lg dark:border-canopy-border dark:bg-canopy-surface sm:p-8">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">One last step</p>
                <h1 class="mt-2 text-fluid-xl font-extrabold">Verify your email address.</h1>
                <p class="mt-3 text-slate-muted dark:text-mist-dark">We sent a confirmation link to {{ auth()->user()->email }}. Open it to activate your profile.</p>
                @if (session('status'))
                    <p class="mt-5 rounded-xl border border-biolum/50 bg-biolum/10 p-4 text-sm font-semibold text-biolum-light" role="status">{{ session('status') }}</p>
                @endif
                <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="ada-focus min-h-touch rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover">Resend verification email</button>
                </form>
            </section>
        </main>
        <x-mobile-navigation-footer active="profile" />
    </body>
</html>
