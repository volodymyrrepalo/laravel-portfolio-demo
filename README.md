# Mushroom Glades

Mushroom Glades is a responsive, mobile-first field journal for mushroom
foragers. It lets each forager keep a private map of promising spots, add
notes about what they found, filter those notes, and save favorite locations
for quick return visits.

## Live Demo

Explore the working application at
[mushroomglades.infinityfree.io](https://mushroomglades.infinityfree.io/).

## Features

- Create a verified account and manage account details securely.
- Add, edit, and delete private map pins with optional foraging notes.
- Filter saved pins instantly with a case-insensitive search over comments.
- Add useful pins to a personal Favorites list and open them directly on the
  map.
- Keep pins and favorites private to their owner.
- Use the application comfortably on phones, tablets, and desktop screens with
  light and dark themes.

## Tech Stack

- [Laravel 13](https://laravel.com/) and [PHP 8.3](https://www.php.net/)
- [Livewire 4](https://livewire.laravel.com/) for server-driven interactions
- [MySQL 8](https://www.mysql.com/) for application data
- [Leaflet](https://leafletjs.com/) with [OpenStreetMap](https://www.openstreetmap.org/)
  tiles for the map
- [Tailwind CSS 4](https://tailwindcss.com/) and [Vite](https://vite.dev/) for
  responsive UI and assets
- [Laravel Reverb](https://reverb.laravel.com/) for WebSocket capability
- [Lando](https://lando.dev/) for local containers, with Mailpit for local mail
  inspection

## Folder Structure

```text
app/                    Laravel application code, models, policies, and controllers
database/migrations/    Versioned database schema
docs/architecture/      Architecture notes and ADRs
resources/views/        Blade pages and Livewire components
resources/js/           Browser-side integrations, including Leaflet
tests/Feature/          Application feature tests
.lando.yml              Lando PHP, MySQL, Node, and Mailpit configuration
```

## Local Setup

### Requirements

- [Lando](https://docs.lando.dev/getting-started/installation.html)
- Docker, as required by Lando

### Start the application

```bash
git clone <your-github-repository-url>
cd lararepalo
cp .env.example .env
lando start
lando composer install
lando npm ci
lando artisan key:generate
lando artisan migrate
lando npm run build
```

Run `lando info` to find the local application and Mailpit URLs. For active
frontend development, run `lando npm run dev` in a separate terminal. Laravel
Reverb can be started with `lando reverb` when working on WebSocket features.

## Security

- Map pins and favorites are authorized per user and are not public.
- Protected pages require an authenticated, verified email address.
- Passwords use Laravel's secure hashing, with confirmation and strength
  validation for registration and password changes.
- Changing an email address requires the current password and verification of
  the new address.
- Password changes invalidate other active sessions; sensitive actions such as
  account deletion require the current password.
- Authentication and registration attempts are rate limited.
- `.env` files, dependencies, compiled assets, and real database data are not
  committed. The included SQL snapshot contains schema only.

## License

This project is released under the [MIT License](LICENSE).
