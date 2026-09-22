# ADR 0003: Use Laravel Reverb for real-time features

- Status: Accepted
- Date: 2026-09-22
- Deciders: Project owner

## Context

The application requires WebSocket connectivity for real-time features. The
solution should integrate with Laravel broadcasting and work in the local
development environment without depending on a third-party hosted service.

## Decision

Use Laravel Reverb as the WebSocket server and Laravel's `reverb` broadcast
connection. In Lando, run it with `lando reverb`; the server listens internally
on port 8080 and is reached through the Lando Reverb hostname.

## Consequences

- Real-time events use Laravel's native broadcasting workflow.
- Development requires a separate long-running Reverb process while testing
  real-time behavior.
- The public Reverb host and scheme are environment configuration, not
  application code, so they can differ between local and production.

## Alternatives considered

- Pusher or another hosted WebSocket provider: not selected for the initial
  setup because Reverb keeps the development stack self-contained.
- A custom WebSocket server: not selected because Reverb already integrates
  with Laravel broadcasting and application credentials.
