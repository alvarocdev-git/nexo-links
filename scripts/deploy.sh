#!/usr/bin/env bash
# Server-side deploy helper: run from the app root over SSH.
set -euo pipefail
cd "$(dirname "$0")/.."

php artisan down --retry=30 || true

git pull origin main
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan up

echo "✓ Deployed $(git rev-parse --short HEAD)"
