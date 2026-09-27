# ADR 0007: Use Livewire profile access and email verification

- Status: Accepted
- Date: 2026-09-27
- Deciders: Project owner

## Context

The portfolio needs an account entry point before the full Profile feature is
available. It must support password-based registration and sign-in, require a
verified email before Profile access, and allow a person to confirm their email
from the browser where they open the message.

## Decision

Use a Livewire component for the guest-facing sign-in and registration forms,
embedded in a Laravel-rendered Profile access page. Registration collects a
display name, email address, password, and password confirmation. Passwords
must contain at least eight characters, letters, numbers, and symbols.

Use Laravel's session guard, `MustVerifyEmail` contract, `Registered` event,
and default verification notification. The verification route accepts Laravel's
temporary signed URL without requiring an existing session, verifies the email
hash, authenticates that account, regenerates the session, and renders an email
confirmation page. This makes the signed email link a short-lived bearer
credential and lets it work in a different browser or device.

`/profile` renders the access form for guests, redirects authenticated but
unverified users to a notice and resend flow, and renders only a placeholder for
verified users until the full Profile page is implemented.

Local development uses Lando Mailpit to capture verification messages. Laravel
uses SMTP through the `mailpit` service locally; production mail settings remain
environment-specific.

## Consequences

- Profile access is blocked until email verification completes.
- Sign-in, registration, resend, and signed-link endpoints are rate-limited.
- Email verification does not require a person to return to the original
  browser, but possession of the unexpired link grants an authenticated session.
- Mailpit provides a safe local inbox and does not deliver messages externally.
- Password recovery, logout controls, profile editing, and production SMTP
  provider selection remain future work.

## Alternatives considered

- Install a Laravel starter kit: not selected because the application only needs
  a focused access flow and already includes Livewire.
- Use Laravel's default same-session verification handler: not selected because
  it does not meet the cross-browser confirmation requirement.
- Use an external authentication provider: not selected because email/password
  credentials and the existing Laravel user model are sufficient at this stage.
