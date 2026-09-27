<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component
{
    public string $mode = 'login';

    public string $loginEmail = '';

    public string $loginPassword = '';

    public string $registrationName = '';

    public string $registrationEmail = '';

    public string $registrationPassword = '';

    public string $registrationPasswordConfirmation = '';

    public function showLogin(): void
    {
        $this->mode = 'login';
        $this->resetErrorBag();
    }

    public function showRegistration(): void
    {
        $this->mode = 'registration';
        $this->resetErrorBag();
    }

    public function login(): void
    {
        $validated = $this->validate([
            'loginEmail' => ['required', 'email'],
            'loginPassword' => ['required'],
        ]);

        $key = $this->loginLimiterKey($validated['loginEmail']);
        $this->ensureNotRateLimited($key, 'loginEmail');

        if (! Auth::attempt(['email' => $validated['loginEmail'], 'password' => $validated['loginPassword']])) {
            RateLimiter::hit($key, 60);
            $this->loginPassword = '';

            throw ValidationException::withMessages([
                'loginEmail' => 'The provided credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($key);
        session()->regenerate();

        $this->redirectRoute('profile', navigate: false);
    }

    public function register(): void
    {
        $validated = $this->validate([
            'registrationName' => ['required', 'string', 'max:255'],
            'registrationEmail' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'registrationPassword' => [
                'required',
                'same:registrationPasswordConfirmation',
                Password::min(8)->letters()->numbers()->symbols(),
            ],
            'registrationPasswordConfirmation' => ['required'],
        ]);

        $key = $this->registrationLimiterKey();
        $this->ensureNotRateLimited($key, 'registrationEmail');
        RateLimiter::hit($key, 60);

        $user = User::query()->create([
            'name' => $validated['registrationName'],
            'email' => $validated['registrationEmail'],
            'password' => $validated['registrationPassword'],
        ]);

        event(new Registered($user));
        Auth::login($user);
        session()->regenerate();

        $this->redirectRoute('verification.notice', navigate: false);
    }

    private function ensureNotRateLimited(string $key, string $field): void
    {
        if (! RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        throw ValidationException::withMessages([
            $field => 'Too many attempts. Please try again in '.RateLimiter::availableIn($key).' seconds.',
        ]);
    }

    private function loginLimiterKey(string $email): string
    {
        return 'profile-access:login:'.Str::lower($email).'|'.request()->ip();
    }

    private function registrationLimiterKey(): string
    {
        return 'profile-access:registration:'.request()->ip();
    }
};
?>

<div class="mx-auto w-full max-w-xl rounded-3xl border border-mist-border bg-mist-surface p-6 shadow-lg dark:border-canopy-border dark:bg-canopy-surface sm:p-8">
    <div class="mb-6 text-center">
        <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Profile access</p>
        <h1 class="mt-2 text-fluid-xl font-extrabold">Grow your field journal.</h1>
        <p class="mt-2 text-sm text-slate-muted dark:text-mist-dark">Sign in to continue or create an account to begin.</p>
    </div>

    <div class="grid grid-cols-2 rounded-2xl bg-mist p-1 dark:bg-canopy" role="tablist" aria-label="Profile access options">
        <button id="login-tab" type="button" wire:click="showLogin" role="tab" aria-controls="login-panel" aria-selected="{{ $mode === 'login' ? 'true' : 'false' }}" class="ada-focus min-h-touch rounded-xl px-4 text-sm font-bold {{ $mode === 'login' ? 'bg-mist-surface text-spore shadow-sm dark:bg-canopy-surface dark:text-chanterelle-light' : 'text-slate-muted dark:text-mist-dark' }}">Sign in</button>
        <button id="registration-tab" type="button" wire:click="showRegistration" role="tab" aria-controls="registration-panel" aria-selected="{{ $mode === 'registration' ? 'true' : 'false' }}" class="ada-focus min-h-touch rounded-xl px-4 text-sm font-bold {{ $mode === 'registration' ? 'bg-mist-surface text-spore shadow-sm dark:bg-canopy-surface dark:text-chanterelle-light' : 'text-slate-muted dark:text-mist-dark' }}">Create account</button>
    </div>

    @if ($mode === 'login')
        <form id="login-panel" wire:submit="login" class="mt-6 space-y-5" role="tabpanel" aria-labelledby="login-tab">
            <div>
                <label for="login-email" class="text-sm font-bold">Email address</label>
                <input id="login-email" wire:model="loginEmail" type="email" autocomplete="email" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                @error('loginEmail') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="login-password" class="text-sm font-bold">Password</label>
                <input id="login-password" wire:model="loginPassword" type="password" autocomplete="current-password" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                @error('loginPassword') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="ada-focus min-h-touch w-full rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover" wire:loading.attr="disabled">Sign in</button>
        </form>
    @else
        <form id="registration-panel" wire:submit="register" class="mt-6 space-y-5" role="tabpanel" aria-labelledby="registration-tab">
            <div>
                <label for="registration-name" class="text-sm font-bold">Display name</label>
                <input id="registration-name" wire:model="registrationName" type="text" autocomplete="name" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                @error('registrationName') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="registration-email" class="text-sm font-bold">Email address</label>
                <input id="registration-email" wire:model="registrationEmail" type="email" autocomplete="email" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                @error('registrationEmail') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="registration-password" class="text-sm font-bold">Password</label>
                <input id="registration-password" wire:model="registrationPassword" type="password" autocomplete="new-password" aria-describedby="password-requirements" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                <p id="password-requirements" class="mt-2 text-sm text-slate-muted dark:text-mist-dark">Use at least 8 characters, including letters, numbers, and symbols.</p>
                @error('registrationPassword') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="registration-password-confirmation" class="text-sm font-bold">Confirm password</label>
                <input id="registration-password-confirmation" wire:model="registrationPasswordConfirmation" type="password" autocomplete="new-password" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                @error('registrationPasswordConfirmation') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="ada-focus min-h-touch w-full rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover" wire:loading.attr="disabled">Create account</button>
        </form>
    @endif
</div>
