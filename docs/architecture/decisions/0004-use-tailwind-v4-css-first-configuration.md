# ADR 0004: Use Tailwind CSS v4's CSS-first configuration

- Status: Accepted
- Date: 2026-09-23
- Deciders: Project owner

## Context

The application uses Tailwind CSS v4 through Vite. An initial JavaScript
Tailwind configuration supplied the project's visual palette, fluid typography,
touch-target sizing, custom shadows, an accessible focus treatment, and
class-based dark mode.

## Decision

Keep Tailwind configuration in `resources/css/app.css` using v4's `@theme`,
`@custom-variant`, and `@utility` directives. The former JavaScript
`tailwind.config.js` is not used.

## Consequences

- The biophilic palette and design tokens are available as Tailwind utilities,
  such as `bg-canopy`, `text-spore`, `text-fluid-xl`, `min-h-touch`, and
  `shadow-glow`.
- The `.dark` class enables dark-mode utilities.
- The `ada-focus` utility provides a visible keyboard-focus treatment.
- Tailwind configuration remains colocated with the stylesheet Vite compiles.

## Alternatives considered

- Retain the JavaScript configuration: not selected because it is the Tailwind
  v3 configuration model and duplicates the v4 CSS-first setup.
- Use arbitrary values for individual tokens: not selected because the shared
  palette and accessibility utilities need consistent names.
