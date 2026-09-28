# ADR 0010: Use Livewire profile account management

- Status: Accepted
- Date: 2026-09-28
- Deciders: Project owner

## Context

Verified users need to manage their private account details while the field
journal remains centered on their own map pins and favorites. Sensitive changes
must not weaken the existing email-verification or session-security model.

## Decision

Use a Livewire dashboard at `/profile` for account details, password changes,
private activity totals, logout, and permanent account deletion. Updating an
email address requires the current password, marks the new address unverified,
and sends Laravel's normal verification notification. Password changes require
the current password and invalidate other sessions through Laravel's session
authentication middleware.

Account deletion requires the current password and removes the user and the
dependent private records through existing database foreign-key cascades.

## Consequences

- No new profile columns, public profile, avatar, or biography are introduced.
- An email change blocks protected profile, map, and favorites access until the
  new address is verified.
- Password changes retain the current session and invalidate other sessions.
- Account deletion is permanent and has no recovery period.

## Alternatives considered

- A separate Laravel starter-kit settings implementation: not selected because
  the existing Livewire and Blade application already provides the needed UI
  structure.
- Allowing email changes without a password check: not selected because a
  stolen active session could otherwise change the account identity address.
- Soft-deleting accounts: not selected because the requested behavior is
  permanent deletion of the account and its private field-journal data.
