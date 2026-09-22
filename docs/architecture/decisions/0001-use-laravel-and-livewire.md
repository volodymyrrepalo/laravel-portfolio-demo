# ADR 0001: Use Laravel and Livewire for the application

- Status: Accepted
- Date: 2026-09-22
- Deciders: Project owner

## Context

The portfolio needs a maintainable web application with responsive, interactive
user experiences. The initial project direction calls for Laravel and values
server-driven interaction over a separate single-page application by default.

## Decision

Build the application with Laravel. Use Livewire for interactive user-facing
features, keeping state, validation, authorization, and business workflows in
Laravel unless a browser-only interaction has a clear need for JavaScript.

## Consequences

- Laravel conventions provide routing, persistence, queues, testing, and
  security foundations.
- Livewire reduces duplicated client/server state for typical portfolio and
  administration interactions.
- The team should use Livewire components for dynamic interfaces and introduce
  additional frontend frameworks only when their benefits are demonstrated.

## Alternatives considered

- A separate SPA frontend: not selected because it would add a separate
  application boundary and duplicate interaction state for the initial scope.
- Server-rendered Blade only: not selected because interactive experiences are
  an explicit goal.
