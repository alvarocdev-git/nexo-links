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
- Social icons footer: predefined platforms (Instagram, X, TikTok, YouTube,
  GitHub, LinkedIn, etc.) shown as icons; the owner decides whether a social
  profile lives in the footer or as a regular link button
- Contact link types: phone (`tel:`), email (`mailto:`) and a WhatsApp link
  builder (phone + prefilled message → `wa.me` URL)
- Profile customization: avatar, bio, banner image, page background
  (solid color or gradient) and color palette presets
- Configurable footer attribution per instance (env): defaults to
  "made with Nexo" → repo; the reference deploy shows
  "powered by alvarocdev.com"
- Marketing landing page at `/`: product explanation, login/register CTAs
  and a live example page
- Auto-generated QR code (SVG, server-side, no third-party services)
  downloadable from the dashboard
- Multilingual interface: Spanish, English and Portuguese, with a visible
  language switcher that updates everything instantly (public pages pick
  the visitor's browser language by default)
- Help center ("What can I do in Nexo?"): short guides for every feature —
  create an account, add links, schedule, read analytics, share the QR —
  translatable and linked from the landing and the dashboard
- Report system: visitors can report a page or a specific link (broken,
  malicious, abusive); the page owner sees a notification in their panel
  and the instance admin can review reports
- Production deploy on shared hosting (Hostinger, PHP + MySQL)

## Design principles

- **Mobile-first**: public pages and dashboard are designed for phone
  viewports first — most visitors come from social app in-app browsers
- Distinctive but restrained aesthetics: strong typography, subtle
  micro-animations, first-class dark mode — not another Linktree clone
- Cross-browser: Chrome, Firefox, Safari (incl. iOS) and Edge
- Accessible by default (WCAG AA as the baseline): semantic HTML, visible
  focus states, keyboard navigation, aria labels on icon-only controls,
  reduced-motion support and AA color contrast — plus a dedicated audit
  before v1

## Out of scope (for now)

- Custom domains per user
- Paid plans / monetization features
- Social integrations (auto-import feeds, embeds)
- Native mobile apps
- Teams / multiple editors per page
- Media hosting beyond the avatar

## Planned after v1

- Social login with Google and GitHub (Laravel Socialite); Apple Sign-In
  deliberately skipped at first (requires a paid Apple Developer account)
- Two-factor authentication
- Per-user content translations: owners translate their own bio and link
  titles per language, so visitors see the page in their language

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
