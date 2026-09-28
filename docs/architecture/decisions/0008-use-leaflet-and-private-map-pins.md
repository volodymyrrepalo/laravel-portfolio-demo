# ADR 0008: Use Leaflet and private map pins

- Status: Accepted
- Date: 2026-09-27
- Deciders: Project owner

## Context

Mushroom Glades needs a map where a confirmed account holder can retain the
locations and notes for their own finds. The initial implementation must avoid
an API key and paid mapping account while remaining practical for local
development and early portfolio use.

## Decision

Use Leaflet in the Vite frontend with OpenStreetMap raster tiles. Load only the
current user's pins and store each pin with its owner, latitude, longitude, and
optional plain-text comment. Enforce pin ownership with a Laravel policy and
show the map only after email verification.

Map location is requested only after an explicit user action. The map opens to
the user's saved pins when available, otherwise to a neutral world view.

## Consequences

- The feature does not require a Google Maps key or billing configuration.
- The interface includes OpenStreetMap attribution and must follow the public
  tile service's usage policy. A production tile provider should be selected
  before traffic exceeds that service's best-effort capacity.
- Pins are private in this version. Shared observations, species metadata,
  images, clustering, and offline mapping remain future work.
- Leaflet manages interactive map state while Livewire validates and persists
  server-side changes.

## Alternatives considered

- Google Maps JavaScript API: not selected because standard use requires a
  billing-enabled API key; a demo key is not appropriate for this application.
- A custom map implementation: not selected because Leaflet provides the
  needed marker and tile interactions with less application code.
- Public pins: not selected because the current field journal is personal and
  private by default.
