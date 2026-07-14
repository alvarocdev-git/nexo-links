# Nexo

**Your links, your domain, and visitor analytics without spying on anyone.**

Nexo is an open-source, self-hosted link-in-bio platform — a Linktree alternative
you run on your own domain and infrastructure, designed to work even on cheap
shared hosting (PHP + MySQL).

> 🚧 Under active development. Not ready for production yet.

## Why Nexo?

- **No vendor lock-in** — your page lives on *your* domain. No platform can take
  it away, paywall it, or shut it down.
- **Privacy-first analytics** — click stats without cookies and without storing
  personal data. No consent banner needed.
- **Fast by design** — server-rendered public pages, minimal JavaScript,
  targeting Lighthouse 100.
- **Dynamic links** — schedule links by date, highlight what's live now, add
  countdowns for events.

## Tech stack

Laravel 13 · MySQL 8 · Blade + Alpine.js + Tailwind CSS · Vite

Quality tooling: [Pint](https://laravel.com/docs/pint) (code style),
[Larastan](https://github.com/larastan/larastan) (static analysis),
[Pest](https://pestphp.com) (tests), GitHub Actions (CI).

## Local development

Requirements: Docker (the dev environment runs on [Laravel Sail](https://laravel.com/docs/sail)).

```bash
cp .env.example .env

# Install PHP dependencies without a local PHP install
docker run --rm -v "$(pwd):/app" -w /app composer:latest composer install

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

The app is served at [http://localhost](http://localhost). Mailpit (local email
inbox) at [http://localhost:8025](http://localhost:8025).

### Quality checks

```bash
./vendor/bin/sail composer lint        # fix code style
./vendor/bin/sail composer lint:check  # check code style
./vendor/bin/sail composer analyse     # static analysis
./vendor/bin/sail artisan test         # run tests
```

## Project docs

- [Scope & MVP definition](docs/SCOPE.md)
- [Wireframes](docs/WIREFRAMES.md)

## License

MIT
