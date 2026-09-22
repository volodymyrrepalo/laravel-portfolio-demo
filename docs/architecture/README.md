# Architecture documentation

This directory preserves the reasoning behind the portfolio application's
architecture. It is intentionally lightweight now and is structured to become
an arc42 document set as the application grows.

## How to use it

- Record a durable technical choice as an Architecture Decision Record (ADR) in
  [`decisions/`](decisions/README.md).
- Create a new ADR from [`decisions/template.md`](decisions/template.md) before
  implementing a consequential change.
- Mark an older ADR as *Superseded* instead of rewriting history when a decision
  changes.

## Future arc42 mapping

| arc42 section | Source material |
| --- | --- |
| 1. Introduction and goals | Product and quality goals recorded in ADR context sections |
| 3. Context and scope | Integration and boundary decisions |
| 5. Building block view | Decisions about application structure and modules |
| 6. Runtime view | Decisions about Livewire, queues, broadcasts, and Reverb |
| 7. Deployment view | Lando, MySQL, Node, and production-environment decisions |
| 8. Cross-cutting concepts | Security, testing, accessibility, and coding conventions |
| 9. Architecture decisions | The ADRs in [`decisions/`](decisions/README.md) |
| 10–12. Quality, risks, glossary | Extracted and consolidated when the architecture is mature |
