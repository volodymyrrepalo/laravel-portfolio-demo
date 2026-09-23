# ADR 0005: Enforce quality checks before commits

- Status: Accepted
- Date: 2026-09-23
- Deciders: Project owner

## Context

The application needs fast, consistent feedback before changes enter version
control. JavaScript, Laravel-aware PHP static analysis, and PHP coding standard
checks cover different classes of defects and should run in the same local
environment as the application.

## Decision

Use Husky to run a pre-commit hook that executes these checks through Lando:

- ESLint for JavaScript in `resources/js`.
- Larastan/PHPStan at level 6 for production Laravel code.
- PHP_CodeSniffer using PSR-12 with narrowly scoped Laravel-convention
  exclusions.

The hook is defined in `.husky/pre-commit`. Composer scripts provide
`lint:phpstan` and `lint:phpcs`; npm provides `lint:js`.

## Consequences

- A commit is blocked when any configured check fails.
- All contributors need Git, Lando, and the project's installed dependencies.
- PHPStan does not currently analyse tests because the starter application's
  placeholder unit test asserts a literal value; add tests to PHPStan's paths
  when the test suite contains meaningful application behavior.
- PHPCS keeps Laravel's generated conventions for config string concatenation,
  combined trait imports, and descriptive PHPUnit method names.

## Alternatives considered

- Run checks manually: not selected because omissions are easy during normal
  development.
- Run only Laravel Pint: not selected because formatting does not provide the
  JavaScript linting or Laravel-aware static analysis required here.
- Run checks only in CI: not selected because contributors need feedback before
  pushing; CI remains a recommended second line of enforcement.
