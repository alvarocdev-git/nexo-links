# Nexo — Project Scope

Nexo is an open-source, self-hosted link-in-bio platform (a Linktree alternative).
Users create a public page at `/{username}` with their links, fully under their own
domain and infrastructure.

## Value proposition

> **Your links, your domain, and visitor analytics without spying on anyone.**

What Nexo solves that mainstream alternatives don't:

1. **No vendor lock-in** — self-hosted on your own domain (e.g. `link.alvarocdev.com`).
   Your URL survives any platform shutting down or changing its pricing.
2. **Privacy-first analytics** — click stats without cookies and without storing
   personal data. No consent banner required (GDPR-friendly by design).
3. **Extreme performance** — public pages server-rendered, target < 50 KB and
   Lighthouse 100 across the board.
4. **Dynamic links** — schedule links by date/time, highlight a live link,
   optional countdowns for events.

## MVP (in scope)

- User registration, login, email verification, password reset
- Unique username chosen at sign-up (reserved names blocked)
- Link management dashboard: CRUD, drag & drop reorder, show/hide
- Public profile page at `/{username}`: responsive, SEO + Open Graph meta tags
- Cookieless click analytics: per-link and per-day counts, referrers
- Scheduled links, highlighted link, countdowns
- Profile customization: avatar, bio, color themes
- Production deploy on shared hosting (Hostinger, PHP + MySQL)

## Out of scope (for now)

- Custom domains per user
- Paid plans / monetization features
- Social integrations (auto-import feeds, embeds)
- Native mobile apps
- Teams / multiple editors per page
- Media hosting beyond the avatar

## Tech stack

| Layer      | Choice                              | Why                                               |
| ---------- | ----------------------------------- | ------------------------------------------------- |
| Backend    | Laravel 13 (PHP 8.4)                | Runs natively on shared hosting; batteries included |
| Database   | MySQL 8                             | Native on Hostinger shared plans                  |
| Frontend   | Blade + Alpine.js + Tailwind CSS    | Server-rendered, minimal JS, fast public pages    |
| Dev env    | Docker via Laravel Sail             | Reproducible local environment                    |
| Quality    | Pint, Larastan, Pest, GitHub Actions CI | Consistent style, static analysis, tests      |

## Conventions

- Commits follow [Conventional Commits](https://www.conventionalcommits.org/)
  (`feat:`, `fix:`, `chore:`, `docs:`, `perf:`, `test:`)
- One commit per completed phase (see the project plan)
- Code, identifiers and docs in English
