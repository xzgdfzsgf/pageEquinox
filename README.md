# EQUINOX — Landing page

**Where Space Meets at the Equator** · March 16–19, 2027 · Quito, Ecuador
Event by Fundación Sideralis. Laravel 12 (PHP 8.2+), Blade, plain CSS/JS built with Vite.

## Local development

```bash
composer install
npm install
cp .env.example .env && php artisan key:generate
php artisan migrate
npm run build        # or: npm run dev
php artisan serve
```

By default `.env` uses SQLite (`database/database.sqlite`). To use MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=equinox
DB_USERNAME=...
DB_PASSWORD=...
```

Tests: `php artisan test`

## Structure

| Area | Files |
|---|---|
| Page (the 11 wireframe sections) | `resources/views/landing.blade.php` |
| Forms | `resources/views/partials/join-form.blade.php`, `sponsor-form.blade.php` |
| Visuals (orbits, globe) | `resources/views/partials/orbits.blade.php`, `globe.blade.php` |
| Logos (SVG, currentColor) | `resources/svg/logo-*.svg` (exported from `LOGO EQUINOX.ai`) |
| Styles / brand palette | `resources/css/app.css` |
| Interactions (modals, AJAX forms, animations) | `resources/js/app.js` |
| Backend | `app/Http/Controllers/LandingController.php`, `ExportController.php` |
| Data | `leads` tables (Join the list) and `sponsor_inquiries` (Become a sponsor) |

## Forms

- `POST /join`: *Join the list*. Unique email; submitting it again updates the record.
- `POST /sponsor`: *Become a sponsor*. Stored in the DB and, if `EQUINOX_NOTIFY_EMAILS` is set, sent by email (needs `MAIL_*` configured).
- Both have validation, a honeypot against bots, and rate limiting (10/min per IP).

**CSV exports** (Excel-ready): set `EQUINOX_ADMIN_PASSWORD` in `.env` and open
`/admin/leads.csv` or `/admin/sponsors.csv` (HTTP Basic, user `EQUINOX_ADMIN_USER`).
While the password is empty, these routes return 404.

## Photos

The page works without photos (it shows illustrated placeholders). To use real photos, place them in `public/img/`
with these names and they are picked up automatically:

| File | Where it's used | Suggested size |
|---|---|---|
| `hero.jpg` | Hero background (under a Navy Profundo overlay) | 2400×1400 |
| `conference.jpg` | "The Conference" card (UDLA Park) | 1600×1000 |
| `stargate.jpg` | "The Stargate Experience" card | 1600×1000 |
| `og.jpg` | Preview image when the link is shared | 1200×630 |

## Deployment (production)

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

`.env` for production: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<domain>`, MySQL, `MAIL_*`.
The web server's document root must point to `public/`.
