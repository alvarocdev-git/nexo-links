<div align="center">

<img src="resources/brand/mark.svg" width="88" alt="Nexo Links isotype">

# Nexo Links

**Your links. Your domain. Your data.**

[![CI](https://github.com/nexo-tools/nexo-links/actions/workflows/ci.yml/badge.svg)](https://github.com/nexo-tools/nexo-links/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

</div>

---

Nexo Links is an open-source, self-hosted link-in-bio platform — a Linktree
alternative you run on your own domain and infrastructure, designed to work
even on cheap shared hosting (PHP + MySQL).

## Why Nexo Links?

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
- **Multilingual** — English, Spanish and Portuguese (`en`/`es`/`pt`) out of
  the box, with a visible switcher; public pages follow the visitor's browser
  language.
- **Part of the Nexo ecosystem** — the owner dashboard wears the shared Nexo
  chrome (violet brand, light/dark toggle, app-switcher and footer that link
  the other tools), while every public link-in-bio page keeps its own
  configurable per-page theme.
- **Community-safe** — anonymous report system for broken or abusive links,
  with owner notifications in the dashboard.
- **Accessible** — WCAG AA baseline: keyboard navigation, focus rings,
  labels, reduced-motion support and AA contrast.

## Tech stack

Laravel 13 · MySQL 8 · Blade + Alpine.js + Tailwind CSS · Vite

Quality: [Pint](https://laravel.com/docs/pint) ·
[Larastan](https://github.com/larastan/larastan) ·
[Pest](https://pestphp.com) (200+ tests) · GitHub Actions CI

## Quick start (local)

Requirements: Docker — everything else runs in containers via
[Laravel Sail](https://laravel.com/docs/sail).

```bash
git clone https://github.com/nexo-tools/nexo-links.git
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

Nexo Links runs on any host with PHP 8.3+ and MySQL — including shared hosting.
See the step-by-step guide: **[docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)**.

### Configuration

| Env var | Purpose | Default |
| --- | --- | --- |
| `APP_TIMEZONE` | Timezone for link scheduling | `UTC` |
| `NEXO_ATTRIBUTION_LABEL` | Public page footer text | `made with Nexo Links` |
| `NEXO_ATTRIBUTION_URL` | Footer link target | this repo |
| `NEXO_EXAMPLE_USERNAME` | Page linked as the landing's live example | `demo` |
| `NEXO_ECOSYSTEM_CURRENT` | This tool's key in the shared app-switcher | `nexolinks` |
| `NEXO_SUPPORT_EMAIL` | Contact address on the /help center | `hola@alvarocdev.com` |

Theme presets, social platforms, reserved usernames, report reasons and
locales live in [config/nexo.php](config/nexo.php).

## Project docs

- [Scope & roadmap](docs/SCOPE.md)
- [Wireframes](docs/WIREFRAMES.md)
- [Deployment guide](docs/DEPLOYMENT.md)
- [Contributing](CONTRIBUTING.md)

## Nexo ecosystem

Nexo is a family of open-source, self-hostable tools that share one visual identity
([nexo-brand](https://github.com/nexo-tools)), one optional account
([Nexo ID](https://github.com/nexo-tools/nexo-id) SSO) and one set of engineering
standards. Every tool runs **fully standalone** — the ecosystem is opt-in.

| Tool | What it is | Repo |
| --- | --- | --- |
| **Nexo Tools** | Ecosystem hub — discover the tools and hop between them with one account | [nexo-tools](https://github.com/nexo-tools/nexo-tools) |
| **Nexo Links** | Link-in-bio you host yourself (Linktree alternative) | — you are here |
| **Nexo Agenda** | Bookings for service businesses (AgendaPro / Fresha / Booksy alternative) | [nexo-agenda](https://github.com/nexo-tools/nexo-agenda) |
| **Nexo Short** | Self-hosted URL shortener | [nexo-short](https://github.com/nexo-tools/nexo-short) |
| **Nexo Events** | Event tickets and passes | [nexo-events](https://github.com/nexo-tools/nexo-events) |
| **Nexo ID** | One account for every tool — OAuth 2.0 / OIDC SSO | [nexo-id](https://github.com/nexo-tools/nexo-id) |

New to Nexo? Start at **[nexotools.alvarocdev.com](https://nexotools.alvarocdev.com)**.
Built by **[alvarocdev.com](https://alvarocdev.com)** — the tech behind Nexo.

## License & credits

[MIT](LICENSE). Built by **Alvaro Carrizales** — [alvarocdev.com](https://alvarocdev.com).
