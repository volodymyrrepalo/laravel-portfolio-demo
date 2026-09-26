# ADR 0006: Use Blade mushroom cards and an accessible carousel

- Status: Accepted
- Date: 2026-09-26
- Deciders: Project owner

## Context

The portfolio needs a presentational mushroom card that can later receive data
from the database, plus a reusable one-card-at-a-time species carousel. The
style guide is the initial consumer. The interface must be usable with a
keyboard and should not introduce a JavaScript carousel dependency for a small,
well-defined interaction.

## Decision

Use anonymous Blade components for the card and carousel:

- `x-mushroom-card` receives `name`, `scientificName`, `summary`, `imageUrl`,
  `imageAlt`, and `wikipediaUrl`, plus optional `creditUrl` and `creditLabel`.
- `x-mushroom-carousel` receives a `mushrooms` array using that same shape and
  an optional accessible `label`.
- The carousel uses the existing Vite toolchain and a small vanilla JavaScript
  module. It shows one card at a time, has previous/next controls, disabled
  boundary controls, a live slide status, and Left/Right Arrow key support.
- The carousel is a labelled region with slide position labels and visible
  keyboard focus. It has no autoplay or motion transition.
- External Wikipedia and image-credit links open in a new tab with safe
  `rel` attributes and an accessible indication. Image credits remain visible.

The style guide initially supplies three Wikimedia Commons fixtures—Golden
Chanterelle, Fly Agaric, and Common Morel. These are presentation fixtures
only; application models, migrations, storage, and database queries are
deferred.

## Consequences

- Future database-backed records can populate the card and carousel without
  changing their public Blade interfaces.
- Attribution remains available wherever the supplied credit values are shown.
- The UI avoids an additional package and its maintenance cost, but the local
  JavaScript module must continue to be covered when behavior changes.
- The one-card interaction is intentionally not a multi-card responsive grid;
  a separate component should be introduced if that becomes a product need.

## Alternatives considered

- Add a third-party carousel library: not selected because the required
  interaction is small and can be implemented accessibly with existing tools.
- Use a Livewire component: not selected because switching already-provided
  card data does not need a server round trip.
- Build models and migrations now: not selected because the data design and
  production image-storage requirements are not yet defined.
