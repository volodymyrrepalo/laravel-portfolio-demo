# ADR 0002: Use Lando for local development

- Status: Accepted
- Date: 2026-09-22
- Deciders: Project owner

## Context

Local development needs repeatable services and versions without relying on
each developer's host PHP, MySQL, or Node installation. The application needs
PHP, MySQL, Node-based frontend tooling, and a route for the real-time server.

## Decision

Use Lando as the local development environment. The project configuration
provides PHP 8.3, MySQL 8.0, and Node 22 services. Use the Laravel MySQL
service credentials in the local environment and run frontend tooling through
the Node service.

## Consequences

- `lando start` gives contributors a consistent service topology.
- Laravel commands run through `lando artisan`; Composer through `lando
  composer`; and frontend tooling through `lando npm`.
- Docker and Lando are prerequisites for local development.
- Local HTTPS depends on trusting Lando's development certificate authority;
  HTTP remains available for development when that has not been completed.

## Alternatives considered

- Host-installed PHP, MySQL, and Node: not selected because version and setup
  drift would be likely across contributors.
- Docker Compose maintained directly: not selected because Lando provides the
  Laravel-oriented tooling and local proxy workflow needed for this project.
