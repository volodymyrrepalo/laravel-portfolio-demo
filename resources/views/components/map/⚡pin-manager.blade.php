<?php

use App\Models\MapPin;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

new class extends Component
{
    public ?int $selectedPinId = null;

    public ?float $latitude = null;

    public ?float $longitude = null;

    public string $comment = '';

    public bool $pinFormOpen = false;

    public bool $selectedPinIsFavorite = false;

    public string $statusMessage = '';

    public function beginCreating(float $latitude, float $longitude): void
    {
        Gate::forUser($this->currentUser())->authorize('create', MapPin::class);

        $this->selectedPinId = null;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->comment = '';
        $this->selectedPinIsFavorite = false;
        $this->statusMessage = '';
        $this->pinFormOpen = true;
        $this->resetErrorBag();
    }

    public function selectPin(int $pinId): void
    {
        $mapPin = $this->findPin($pinId);

        Gate::forUser($this->currentUser())->authorize('update', $mapPin);

        $this->selectedPinId = $mapPin->id;
        $this->latitude = $mapPin->latitude;
        $this->longitude = $mapPin->longitude;
        $this->comment = $mapPin->comment ?? '';
        $this->selectedPinIsFavorite = $this->currentUser()
            ->favoriteMapPins()
            ->whereKey($mapPin)
            ->exists();
        $this->statusMessage = '';
        $this->pinFormOpen = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        if ($this->selectedPinId !== null) {
            $this->updateSelectedPin();

            return;
        }

        $validated = $this->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $this->currentUser();
        Gate::forUser($user)->authorize('create', MapPin::class);

        $user->mapPins()->create([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'comment' => $this->nullableComment($validated['comment']),
        ]);

        $this->statusMessage = 'Pin saved to your map.';
        $this->closePinForm();
        $this->dispatchPinsUpdated();
    }

    public function deleteSelectedPin(): void
    {
        abort_unless($this->selectedPinId !== null, 404);

        $mapPin = $this->findPin($this->selectedPinId);

        Gate::forUser($this->currentUser())->authorize('delete', $mapPin);

        $mapPin->delete();

        $this->statusMessage = 'Pin removed from your map.';
        $this->closePinForm();
        $this->dispatchPinsUpdated();
    }

    public function cancelPinForm(): void
    {
        $this->closePinForm();
        $this->resetErrorBag();
    }

    public function toggleSelectedFavorite(): void
    {
        abort_unless($this->selectedPinId !== null, 404);

        $user = $this->currentUser();
        $mapPin = $this->findPin($this->selectedPinId);

        Gate::forUser($user)->authorize('update', $mapPin);

        if ($this->selectedPinIsFavorite) {
            $user->favoriteMapPins()->detach($mapPin);
            $this->selectedPinIsFavorite = false;
            $this->statusMessage = 'Pin removed from your favorites.';

            return;
        }

        $user->favoriteMapPins()->syncWithoutDetaching([$mapPin->id]);
        $this->selectedPinIsFavorite = true;
        $this->statusMessage = 'Pin added to your favorites.';
    }

    /**
     * Get the user's pins in the format the map needs.
     *
     * @return array<int, array{id: int, latitude: float, longitude: float, comment: ?string}>
     */
    public function pins(): array
    {
        return $this->currentUser()
            ->mapPins()
            ->latest('created_at')
            ->latest('id')
            ->get(['id', 'user_id', 'latitude', 'longitude', 'comment'])
            ->map(fn (MapPin $mapPin): array => [
                'id' => $mapPin->id,
                'latitude' => $mapPin->latitude,
                'longitude' => $mapPin->longitude,
                'comment' => $mapPin->comment,
            ])
            ->all();
    }

    private function updateSelectedPin(): void
    {
        $validated = $this->validate([
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $mapPin = $this->findPin($this->selectedPinId);

        Gate::forUser($this->currentUser())->authorize('update', $mapPin);

        $mapPin->update([
            'comment' => $this->nullableComment($validated['comment']),
        ]);

        $this->statusMessage = 'Pin comment updated.';
        $this->closePinForm();
        $this->dispatchPinsUpdated();
    }

    private function closePinForm(): void
    {
        $this->selectedPinId = null;
        $this->latitude = null;
        $this->longitude = null;
        $this->comment = '';
        $this->selectedPinIsFavorite = false;
        $this->pinFormOpen = false;
    }

    private function currentUser(): User
    {
        $user = auth()->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }

    private function dispatchPinsUpdated(): void
    {
        $this->dispatch('map-pins-updated', pins: $this->pins());
    }

    private function findPin(int $pinId): MapPin
    {
        return MapPin::query()->findOrFail($pinId);
    }

    private function nullableComment(?string $comment): ?string
    {
        $comment = trim((string) $comment);

        return $comment === '' ? null : $comment;
    }
};
?>

<div
    class="mt-8"
>
    <div
        wire:ignore
    x-data="mapPinManager($wire, @js($this->pins()))"
    x-init="init()"
    x-on:map-pins-updated.window="replacePins($event.detail.pins)"
>
    <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
        <div>
            <label for="map-pin-filter" class="text-sm font-bold">Filter your pins</label>
            <input id="map-pin-filter" x-model="filter" x-on:input="filterPins()" type="search" placeholder="Search comments" class="ada-focus mt-2 block h-12 w-full rounded-xl border border-mist-border bg-mist-surface px-3 dark:border-canopy-border dark:bg-canopy-surface" aria-describedby="map-pin-filter-help">
            <p id="map-pin-filter-help" class="mt-2 text-sm text-slate-muted dark:text-mist-dark">Only pins whose comments contain this text are shown.</p>
        </div>
        <button type="button" x-on:click="useCurrentLocation()" class="ada-focus min-h-touch rounded-xl border border-spore px-5 font-bold text-spore hover:bg-spore/10 dark:border-biolum dark:text-biolum-light dark:hover:bg-biolum/10">Use my location</button>
    </div>

    <p class="mt-3 text-sm font-semibold text-slate-muted dark:text-mist-dark" aria-live="polite" x-text="mapStatus"></p>
    <p class="sr-only" aria-live="polite" x-text="`${visiblePinCount()} pin${visiblePinCount() === 1 ? '' : 's'} visible`"></p>

    <div class="relative mt-5 overflow-hidden rounded-3xl border border-mist-border bg-mist-surface shadow-lg dark:border-canopy-border dark:bg-canopy-surface">
        <div x-ref="map" class="h-[min(62vh,38rem)] min-h-96 w-full" aria-label="Your saved mushroom locations. Select an existing pin to edit it, or select a place on the map to add a pin." role="application"></div>
    </div>

    <p class="mt-3 text-sm text-slate-muted dark:text-mist-dark">Select a place on the map to add a pin. Select one of your pins to edit its note.</p>
    </div>

    @if ($statusMessage !== '')
        <p class="mt-4 rounded-xl border border-biolum/50 bg-biolum/10 p-4 text-sm font-semibold text-biolum-dark dark:text-biolum-light" role="status">{{ $statusMessage }}</p>
    @endif

    @if ($pinFormOpen)
        <div class="fixed inset-0 z-[1100] flex items-center justify-center bg-canopy/60 p-4 backdrop-blur-sm">
            <section x-data x-init="$nextTick(() => $refs.mapPinComment.focus())" class="max-h-full w-full max-w-lg overflow-y-auto rounded-3xl border border-mist-border bg-mist-surface p-5 shadow-2xl dark:border-canopy-border dark:bg-canopy-surface sm:p-6" aria-labelledby="map-pin-form-title" aria-modal="true" role="dialog">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-chanterelle">{{ $selectedPinId === null ? 'New map pin' : 'Edit map pin' }}</p>
                        <h2 id="map-pin-form-title" class="mt-1 text-fluid-lg font-extrabold">{{ $selectedPinId === null ? 'Save this location' : 'Update your note' }}</h2>
                    </div>
                    <button type="button" wire:click="cancelPinForm" class="ada-focus min-h-touch rounded-xl px-4 font-bold text-slate-muted hover:bg-mist dark:text-mist-dark dark:hover:bg-canopy-light">Cancel</button>
                </div>

                <form wire:submit="save" class="mt-5 space-y-5">
                    @if ($selectedPinId === null)
                        <p class="rounded-xl bg-mist p-3 text-sm text-slate-muted dark:bg-canopy dark:text-mist-dark">{{ number_format((float) $latitude, 5) }}, {{ number_format((float) $longitude, 5) }}</p>
                    @endif

                    <div>
                        <label for="map-pin-comment" class="text-sm font-bold">Comment <span class="font-normal text-slate-muted dark:text-mist-dark">(optional)</span></label>
                        <textarea id="map-pin-comment" x-ref="mapPinComment" wire:model="comment" rows="4" maxlength="1000" class="ada-focus mt-2 block w-full rounded-xl border border-mist-border bg-mist px-3 py-3 dark:border-canopy-border dark:bg-canopy" placeholder="What did you find here?"></textarea>
                        @error('comment') <p class="mt-2 text-sm font-semibold text-chanterelle" role="alert">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="ada-focus min-h-touch rounded-xl bg-chanterelle px-5 font-bold text-canopy hover:bg-chanterelle-hover" wire:loading.attr="disabled">{{ $selectedPinId === null ? 'Save pin' : 'Save comment' }}</button>
                        @if ($selectedPinId !== null)
                            <button type="button" wire:click="toggleSelectedFavorite" class="ada-focus min-h-touch rounded-xl border border-biolum px-5 font-bold text-biolum-dark hover:bg-biolum/10 dark:text-biolum-light" wire:loading.attr="disabled">{{ $selectedPinIsFavorite ? 'Remove from favorites' : 'Add to favorites' }}</button>
                            <button type="button" wire:click="deleteSelectedPin" wire:confirm="Remove this pin from your map?" class="ada-focus min-h-touch rounded-xl border border-chanterelle px-5 font-bold text-chanterelle-dark hover:bg-chanterelle/10 dark:text-chanterelle-light" wire:loading.attr="disabled">Delete pin</button>
                        @endif
                    </div>
                </form>
            </section>
        </div>
    @endif

</div>
