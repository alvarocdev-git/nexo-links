# Nexo

**Your links. Your domain. Your data.**

Nexo is an open-source, self-hosted link-in-bio platform — a Linktree
alternative you run on your own domain and infrastructure, designed to work
even on cheap shared hosting (PHP + MySQL).

[![CI](https://github.com/alvarocdev-git/nexo-links/actions/workflows/ci.yml/badge.svg)](https://github.com/alvarocdev-git/nexo-links/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Why Nexo?

- **No vendor lock-in** — your page lives on *your* domain. No platform can
  take it away, paywall it, or shut it down.
- **Cookieless analytics** — click totals, unique visitors, daily series and
  top referrers with **zero cookies and zero personal data stored**. Visitor
  hashes rotate daily, so nobody can be tracked across days. No consent
  banner needed.
- **Fast by design** — server-rendered pages cached until content changes, no
  external requests (no CDNs, no font services, no trackers), system fonts,
  automatic dark mode.
- **Links with superpowers** — schedule by date, highlight what's live now,
  tease launches with a countdown that flips to a real button on time.
- **Fully customizable** — avatar, banner, accent palettes, solid or gradient
  backgrounds with automatic contrast so pages stay readable.
- **Social icons footer** — 13 platforms plus email/phone/website, with a
  WhatsApp link builder (country selector + prefilled message).
- **Share anywhere** — server-generated SVG QR code, ready to print.
- **Multilingual** — English, Spanish and Portuguese out of the box, with a
  visible switcher; public pages follow the visitor's browser language.
- **Community-safe** — anonymous report system for broken or abusive links,
  with owner notifications in the dashboard.
- **Accessible** — WCAG AA baseline: keyboard navigation, focus rings,
  labels, reduced-motion support and AA contrast.

## Tech stack

Laravel 13 · MySQL 8 · Blade + Alpine.js + Tailwind CSS · Vite

Quality: [Pint](https://laravel.com/docs/pint) ·
[Larastan](https://github.com/larastan/larastan) ·
[Pest](https://pestphp.com) (170+ tests) · GitHub Actions CI

## Quick start (local)

Requirements: Docker — everything else runs in containers via
[Laravel Sail](https://laravel.com/docs/sail).

```bash
git clone https://github.com/alvarocdev-git/nexo-links.git
cd nexo-links
cp .env.example .env
docker run --rm -v "$(pwd):/app" -w /app composer:latest composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
```

Open [http://localhost](http://localhost) — demo account: `demo@nexo.test` /
`password`. Local email inbox (Mailpit): [http://localhost:8025](http://localhost:8025).

## Self-hosting

Nexo runs on any host with PHP 8.3+ and MySQL — including shared hosting.
See the step-by-step guide: **[docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)**.

### Configuration

| Env var | Purpose | Default |
| --- | --- | --- |
| `APP_TIMEZONE` | Timezone for link scheduling | `UTC` |
| `NEXO_ATTRIBUTION_LABEL` | Public page footer text | `made with Nexo` |
| `NEXO_ATTRIBUTION_URL` | Footer link target | this repo |
| `NEXO_EXAMPLE_USERNAME` | Page linked as the landing's live example | `demo` |

Theme presets, social platforms, reserved usernames, report reasons and
locales live in [config/nexo.php](config/nexo.php).

## Project docs

- [Scope & roadmap](docs/SCOPE.md)
- [Wireframes](docs/WIREFRAMES.md)
- [Deployment guide](docs/DEPLOYMENT.md)
- [Contributing](CONTRIBUTING.md)

## License & credits

[MIT](LICENSE). Built by **Álvaro Carrizales** — [alvarocdev.com](https://alvarocdev.com).
