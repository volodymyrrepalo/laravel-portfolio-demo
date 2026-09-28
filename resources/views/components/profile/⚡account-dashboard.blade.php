<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $currentPasswordForEmail = '';

    public string $currentPassword = '';

    public string $newPassword = '';

    public string $newPasswordConfirmation = '';

    public string $currentPasswordForDeletion = '';

    public string $statusMessage = '';

    public function mount(): void
    {
        $user = $this->currentUser();

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateAccount(): void
    {
        $user = $this->currentUser();
        $emailChanged = $this->email !== $user->email;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user)],
        ];

        if ($emailChanged) {
            $rules['currentPasswordForEmail'] = ['required', 'current_password'];
        }

        $validated = $this->validate($rules);

        $user->name = $validated['name'];

        if (! $emailChanged) {
            $user->save();
            $this->statusMessage = 'Account details saved.';

            return;
        }

        $user->email = $validated['email'];
        $user->markEmailAsUnverified();
        $user->save();

        event(new Registered($user));
        session()->put('auth.access.intended_route', 'profile');
        $this->redirectRoute('verification.notice', navigate: false);
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'currentPassword' => ['required', 'current_password'],
            'newPassword' => [
                'required',
                'same:newPasswordConfirmation',
                Password::min(8)->letters()->numbers()->symbols(),
            ],
            'newPasswordConfirmation' => ['required'],
        ]);

        $user = $this->currentUser();

        Auth::logoutOtherDevices($validated['currentPassword']);
        $user->update(['password' => $validated['newPassword']]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->statusMessage = 'Password updated. Other sessions have been signed out.';
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: false);
    }

    public function deleteAccount(): void
    {
        $this->validate([
            'currentPasswordForDeletion' => ['required', 'current_password'],
        ]);

        $user = $this->currentUser();

        Auth::logout();
        $user->delete();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: false);
    }

    /**
     * Get the user's private field-journal totals.
     *
     * @return array{favorite_pins: int, map_pins: int}
     */
    public function statistics(): array
    {
        $user = $this->currentUser();

        return [
            'favorite_pins' => $user->favoriteMapPins()->count(),
            'map_pins' => $user->mapPins()->count(),
        ];
    }

    private function currentUser(): User
    {
        $user = auth()->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }
};
?>

<div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,0.75fr)]">
    @php($statistics = $this->statistics())

    <div class="space-y-6">
        @if ($statusMessage !== '')
            <p class="rounded-xl border border-biolum/50 bg-biolum/10 p-4 text-sm font-semibold text-biolum-dark dark:text-biolum-light" role="status">{{ $statusMessage }}</p>
        @endif

        <section class="rounded-3xl border border-mist-border bg-mist-surface p-5 shadow-lg dark:border-canopy-border dark:bg-canopy-surface sm:p-6" aria-labelledby="account-details-title">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Account</p>
                <h2 id="account-details-title" class="mt-1 text-fluid-lg font-extrabold">Your details</h2>
                <div class="mt-2 flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-biolum/15 px-3 py-1 text-xs font-bold text-biolum-dark dark:text-biolum-light">Verified email</span>
                    <p class="text-sm text-slate-muted dark:text-mist-dark">Your email is used only for your account.</p>
                </div>
            </div>

            <form wire:submit="updateAccount" class="mt-6 space-y-5">
                <div>
                    <label for="profile-name" class="text-sm font-bold">Display name</label>
                    <input id="profile-name" wire:model="name" type="text" autocomplete="name" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                    @error('name') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="profile-email" class="text-sm font-bold">Email address</label>
                    <input id="profile-email" wire:model="email" type="email" autocomplete="email" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                    @error('email') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
                </div>

                @if ($email !== auth()->user()->email)
                    <div>
                        <label for="profile-email-password" class="text-sm font-bold">Current password</label>
                        <input id="profile-email-password" wire:model="currentPasswordForEmail" type="password" autocomplete="current-password" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                        <p class="mt-2 text-sm text-slate-muted dark:text-mist-dark">Changing your email requires confirmation from the new address.</p>
                        @error('currentPasswordForEmail') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
                    </div>
                @endif

                <button type="submit" class="ada-focus min-h-touch rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover" wire:loading.attr="disabled">Save details</button>
            </form>
        </section>

        <section class="rounded-3xl border border-mist-border bg-mist-surface p-5 shadow-lg dark:border-canopy-border dark:bg-canopy-surface sm:p-6" aria-labelledby="password-title">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Security</p>
            <h2 id="password-title" class="mt-1 text-fluid-lg font-extrabold">Change password</h2>

            <form wire:submit="updatePassword" class="mt-6 space-y-5">
                <div>
                    <label for="current-password" class="text-sm font-bold">Current password</label>
                    <input id="current-password" wire:model="currentPassword" type="password" autocomplete="current-password" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                    @error('currentPassword') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-password" class="text-sm font-bold">New password</label>
                    <input id="new-password" wire:model="newPassword" type="password" autocomplete="new-password" aria-describedby="profile-password-requirements" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                    <p id="profile-password-requirements" class="mt-2 text-sm text-slate-muted dark:text-mist-dark">Use at least 8 characters, including letters, numbers, and symbols.</p>
                    @error('newPassword') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="new-password-confirmation" class="text-sm font-bold">Confirm new password</label>
                    <input id="new-password-confirmation" wire:model="newPasswordConfirmation" type="password" autocomplete="new-password" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist px-3 dark:border-canopy-border dark:bg-canopy" required>
                    @error('newPasswordConfirmation') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="ada-focus min-h-touch rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover" wire:loading.attr="disabled">Update password</button>
            </form>
        </section>

        <section class="rounded-3xl border border-chanterelle/50 bg-chanterelle/10 p-5 dark:border-chanterelle/40 sm:p-6" aria-labelledby="danger-zone-title">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle-dark dark:text-chanterelle-light">Danger zone</p>
            <h2 id="danger-zone-title" class="mt-1 text-fluid-lg font-extrabold">Delete account</h2>
            <p class="mt-2 text-sm text-slate-muted dark:text-mist-dark">This permanently removes your account, saved map pins, and favorites.</p>

            <form wire:submit="deleteAccount" class="mt-6 space-y-4">
                <div>
                    <label for="delete-account-password" class="text-sm font-bold">Current password</label>
                    <input id="delete-account-password" wire:model="currentPasswordForDeletion" type="password" autocomplete="current-password" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-chanterelle/50 bg-mist px-3 dark:bg-canopy" required>
                    @error('currentPasswordForDeletion') <p class="mt-2 text-sm font-semibold text-chanterelle-dark dark:text-chanterelle-light" role="alert">{{ $message }}</p> @enderror
                </div>

                <button type="submit" wire:confirm="Permanently delete your account and all of its saved data?" class="ada-focus min-h-touch rounded-xl border border-chanterelle px-5 font-bold text-chanterelle-dark hover:bg-chanterelle/10 dark:text-chanterelle-light" wire:loading.attr="disabled">Delete account</button>
            </form>
        </section>
    </div>

    <aside class="space-y-6">
        <section class="rounded-3xl border border-mist-border bg-mist-surface p-5 shadow-lg dark:border-canopy-border dark:bg-canopy-surface sm:p-6" aria-labelledby="activity-title">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Field journal</p>
            <h2 id="activity-title" class="mt-1 text-fluid-lg font-extrabold">Your activity</h2>
            <dl class="mt-6 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-mist p-4 dark:bg-canopy">
                    <dt class="text-sm font-semibold text-slate-muted dark:text-mist-dark">Saved pins</dt>
                    <dd class="mt-1 text-3xl font-extrabold">{{ $statistics['map_pins'] }}</dd>
                </div>
                <div class="rounded-2xl bg-mist p-4 dark:bg-canopy">
                    <dt class="text-sm font-semibold text-slate-muted dark:text-mist-dark">Favorites</dt>
                    <dd class="mt-1 text-3xl font-extrabold">{{ $statistics['favorite_pins'] }}</dd>
                </div>
            </dl>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('map') }}" class="ada-focus inline-flex min-h-touch items-center rounded-xl border border-spore px-4 font-bold text-spore hover:bg-spore/10 dark:border-biolum dark:text-biolum-light">Open Map</a>
                <a href="{{ route('favorites') }}" class="ada-focus inline-flex min-h-touch items-center rounded-xl border border-spore px-4 font-bold text-spore hover:bg-spore/10 dark:border-biolum dark:text-biolum-light">View favorites</a>
            </div>
        </section>

        <section class="rounded-3xl border border-mist-border bg-mist-surface p-5 shadow-lg dark:border-canopy-border dark:bg-canopy-surface sm:p-6" aria-labelledby="session-title">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">Session</p>
            <h2 id="session-title" class="mt-1 text-fluid-lg font-extrabold">Sign out</h2>
            <p class="mt-2 text-sm text-slate-muted dark:text-mist-dark">End this session on the current device.</p>
            <button type="button" wire:click="logout" class="ada-focus mt-5 min-h-touch rounded-xl border border-spore px-5 font-bold text-spore hover:bg-spore/10 dark:border-biolum dark:text-biolum-light" wire:loading.attr="disabled">Sign out</button>
        </section>
    </aside>
</div>
