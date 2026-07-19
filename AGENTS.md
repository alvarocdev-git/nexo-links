# Nexo Links

> Entry point for any AI/agent working on this project. It follows Alvaro's
> standards system (repo `alvaro`, alvarocdev.com). Keep this file updated:
> persist here any important context that comes up during work sessions.
> Written in English because this is a public open-source repo (deliberate
> exception to the standards system's Spanish-docs rule).

## What is this project

Open-source, self-hosted link-in-bio platform (Linktree alternative): each
user gets a public page at `/{username}` with links, cookieless analytics,
scheduling, themes, social icons, QR sharing and a report system. Multi-user,
multilingual (en/es/pt_BR). **In production** at https://nexolinks.alvarocdev.com
(Alvaro's reference instance, Hostinger shared hosting).

## Stack

- Laravel 13 (framework `^13.8`) · PHP 8.4 in Sail, 8.5 in production
- MySQL 8 · SQLite in-memory for tests
- Blade + Alpine.js + Tailwind CSS v3 + Vite (server-rendered, minimal JS)
- Quality: Pest, Pint, Larastan level 6, GitHub Actions CI
- Hosting: Hostinger shared (LiteSpeed) · mail via smtp.hostinger.com

## How to run it

There is no local PHP/Composer — everything runs through Docker/Sail
(see README for first-time setup). Verified daily commands:

```bash
./vendor/bin/sail up -d                 # start (MySQL + Mailpit)
./vendor/bin/sail artisan migrate --seed  # demo login: demo@nexo.test / password
./vendor/bin/sail npm run build         # or `npm run build` on the host
./vendor/bin/sail artisan test          # Pest suite
./vendor/bin/sail composer lint:check   # Pint (lint = auto-fix)
./vendor/bin/sail composer analyse      # Larastan
```

If default ports are taken by other local projects, this repo's `.env` may
override them (`APP_PORT`, `FORWARD_DB_PORT`, `VITE_PORT`, Mailpit ports) —
the app is then at `http://localhost:<APP_PORT>`.

## Project conventions

- **One Conventional Commit per phase/feature, in English**; tests + Pint +
  Larastan must be green and the change verified end-to-end before committing.
- **Every user-facing string goes through `__()`**. New strings must be added
  (en → es + pt_BR) to `scripts/generate-translations.mjs`, then run it.
  Custom rule messages need `$fail(...)->translate()`.
- **Generated assets are scripted, never hand-edited**: brand/favicons/OG via
  `scripts/generate-brand-assets.mjs` (source: `resources/brand/mark.svg`),
  platform icons via `scripts/generate-social-icons.mjs`.
- **CSP lives in two synced places**: `SecurityHeaders` middleware and
  `public/.htaccess` (re-asserted there because the host overrides PHP-sent
  CSP). A test compares both — change them together.
- **Branding is env-driven, never hardcoded**: `NEXO_ATTRIBUTION_*` controls
  the public footer per instance. Do not hardcode alvarocdev.com in views —
  self-hosted instances must stay neutral (this is why the standards-system
  branding footer component is intentionally NOT installed here).
- New top-level routes need their path added to `reserved_usernames` in
  `config/nexo.php` (public pages are a root-level catch-all).
- Public pages are cached per locale; models that affect them must bust the
  cache (`$touches = ['page']` + `Page::flushCache()` via model events;
  query-builder writes need a manual `$page->touch()`).
- No external requests from any page (no CDNs, no font services) — privacy
  pitch and CSP both depend on it.

## Important decisions

- **2026-07-13** — Laravel + MySQL chosen because the deploy target is
  Hostinger shared hosting (PHP-native, no Node/Docker in production).
- **2026-07-14** — Rebranded "Nexo" → "Nexo Links" to differentiate from the
  Nexo crypto company; "nexolinks" only when a single token is required.
- **2026-07-14** — Attribution footer made configurable per instance by
  design (open source neutrality); Alvaro's instance sets
  "powered by alvarocdev.com" via env.
- **2026-07-19** — Standards-system validation: AGENTS.md created (in
  English, deliberate exception), branding-footer skill satisfied through the
  existing env attribution + UTM params instead of the shared component.

## Accumulated context

- **2026-07-19** — Retroactive open-source audit passed: full git history
  (37 commits) and HEAD checked — no secrets, no `.env` ever committed, no
  real credentials/IPs/server data; only intentional public info (docs use
  Alvaro's domain as the self-hosting example). Informational: commit author
  email is `alvaro@mc4pc.com`; sender mailbox appears in DEPLOYMENT.md's
  env template.
- **2026-07-19** — Standards validation session. Pending on the server:
  update `NEXO_ATTRIBUTION_URL` to
  `https://alvarocdev.com/?utm_source=nexo-links&utm_medium=powered-by`
  (+ `php artisan config:cache`) to match the standard component's UTM
  behavior.
- **2026-07-14** — Hostinger gotchas (all documented in
  `docs/DEPLOYMENT.md`): `proc_open`/`exec` disabled → composer runs with
  `--no-scripts` + manual `php artisan package:discover`; `storage:link`
  replaced by a manual `ln -s`; Force HTTPS replaces the PHP CSP header →
  `.htaccess` re-asserts it. Deploys: `bash scripts/deploy.sh` over SSH +
  upload `public/build` (built locally or by the manual-trigger deploy
  workflow) — no Node on the server.
- **2026-07-14** — Laravel 13 gotchas hit during development: session
  validation errors are plain arrays (use `assertInvalid`), JSON error
  rendering defaults to `api/*` only (overridden in `bootstrap/app.php`),
  unordered `pluck` flaked under SQLite (always order before asserting).
- **2026-07-13** — The seeder is dev-only (demo account). Production's
  landing example comes from `NEXO_EXAMPLE_USERNAME` (set to `alvarocdev`);
  the CTA hides itself if that page doesn't exist.
