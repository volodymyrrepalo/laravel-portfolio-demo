<?php

use App\Models\MapPin;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component
{
    public string $statusMessage = '';

    public function removeFavorite(int $pinId): void
    {
        $user = $this->currentUser();
        $mapPin = $user->favoriteMapPins()->findOrFail($pinId);

        $user->favoriteMapPins()->detach($mapPin);
        $this->statusMessage = 'Pin removed from your favorites.';
    }

    /**
     * Get the user's favorite pins in the order they were saved.
     *
     * @return Collection<int, MapPin>
     */
    public function favoritePins(): Collection
    {
        return $this->currentUser()
            ->favoriteMapPins()
            ->orderByPivot('created_at', 'desc')
            ->orderByDesc('map_pins.id')
            ->get();
    }

    private function currentUser(): User
    {
        $user = auth()->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }
};
?>

<div class="mt-8">
    @php($favoritePins = $this->favoritePins())

    @if ($statusMessage !== '')
        <p class="mb-5 rounded-xl border border-biolum/50 bg-biolum/10 p-4 text-sm font-semibold text-biolum-dark dark:text-biolum-light" role="status">{{ $statusMessage }}</p>
    @endif

    @if ($favoritePins->isEmpty())
        <section class="rounded-3xl border border-dashed border-mist-border bg-mist-surface p-8 text-center dark:border-canopy-border dark:bg-canopy-surface sm:p-12" aria-labelledby="no-favorites-title">
            <span class="text-4xl" aria-hidden="true">🍄</span>
            <h2 id="no-favorites-title" class="mt-4 text-fluid-lg font-extrabold">No favorite pins yet.</h2>
            <p class="mx-auto mt-3 max-w-lg text-slate-muted dark:text-mist-dark">Open your map, select a saved location, and add it to your favorites for quick return trips.</p>
            <a href="{{ route('map') }}" class="ada-focus mt-6 inline-flex min-h-touch items-center rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover">Open Map</a>
        </section>
    @else
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($favoritePins as $pin)
                <x-pin-card :pin="$pin" wire:key="favorite-pin-{{ $pin->id }}">
                    <button type="button" wire:click="removeFavorite({{ $pin->id }})" wire:confirm="Remove this pin from your favorites?" class="ada-focus inline-flex min-h-touch items-center justify-center rounded-xl border border-chanterelle px-4 font-bold text-chanterelle-dark hover:bg-chanterelle/10 dark:text-chanterelle-light" wire:loading.attr="disabled">Remove from favorites</button>
                </x-pin-card>
            @endforeach
        </div>
    @endif
</div>
