# ADR 0009: Use private favorite map pins

- Status: Accepted
- Date: 2026-09-28
- Deciders: Project owner

## Context

Confirmed users can mark their private map pins as favorites and need a quick
way to revisit them without exposing a pin or its comment to another account.
The existing map uses Leaflet with OpenStreetMap and already persists the
user-to-pin favorite relationship.

## Decision

Expose favorites only through the authenticated user's `favoriteMapPins`
relationship. Render the list with a Livewire component so removing a favorite
updates the page immediately; removing it detaches only the pivot record and
does not delete the underlying map pin.

Each favorite links to the existing Leaflet map with an optional pin identifier.
The Map controller accepts that identifier only when it belongs to the current
user, then centers the initial map view on it. Invalid or inaccessible
identifiers use the normal map view without revealing another user's pin.

## Consequences

- No additional persistence, map provider, or third-party dependency is
  required.
- Favorites inherit the existing private-pin ownership boundary.
- The map-focus query parameter is not an authorization mechanism; ownership is
  checked server-side before it reaches the client.
- A future Favorites page can add filters or richer pin metadata without
  changing the favorite relationship.

## Alternatives considered

- A public favorites list: not selected because pin locations and notes remain
  private by default.
- Deleting the pin when it is removed from favorites: not selected because a
  favorite is a bookmark, not ownership of the saved location.
- Google Maps for favorite links: not selected because the existing Leaflet and
  OpenStreetMap implementation meets the requirement without API credentials or
  billing.
