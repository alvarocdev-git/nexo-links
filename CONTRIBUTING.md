# Contributing to Nexo Links

Thanks for your interest! Contributions of every size are welcome — bug
reports, translations, docs and code.

## Development setup

Requirements: Docker. Everything else runs inside containers.

```bash
git clone https://github.com/alvarocdev-git/nexo-links.git
cd nexo-links
cp .env.example .env
docker run --rm -v "$(pwd):/app" -w /app composer:latest composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Demo login: `demo@nexo.test` / `password`.

## Before opening a PR

All three must pass — CI runs them on every push:

```bash
./vendor/bin/sail composer lint:check   # code style (Pint)
./vendor/bin/sail composer analyse      # static analysis (Larastan)
./vendor/bin/sail artisan test          # tests (Pest)
```

New behavior needs tests. Run `composer lint` to auto-fix style.

## Conventions

- [Conventional Commits](https://www.conventionalcommits.org/): `feat:`,
  `fix:`, `chore:`, `docs:`, `perf:`, `test:`
- Code, identifiers and comments in English
- Follow the surrounding code's style — Pint enforces most of it

## Translations

UI strings live in `scripts/generate-translations.mjs` (Nexo Links's own strings)
merged with [laravel-lang](https://github.com/Laravel-Lang/lang) (framework
strings). To fix or add a translation, edit the maps in that script and run:

```bash
node scripts/generate-translations.mjs
```

To propose a new language, open an issue first.

## Social platform icons

Brand SVGs come from [simple-icons](https://github.com/simple-icons/simple-icons).
Add the platform to `scripts/generate-social-icons.mjs`, run it, then register
the platform in `config/nexo.php`.
