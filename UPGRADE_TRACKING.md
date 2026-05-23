# Upgrade Tracking

## Status Saat Ini

Tanggal: 2026-05-23

Project sudah update patch/minor compatible.

Validasi terakhir:

- `vendor/bin/pint --dirty --format agent` — passed
- `npm run build` — passed
- `php artisan test --compact` — passed
- Tests: `17 passed (59 assertions)`
- NPM audit: `0 vulnerabilities`

## Versi Saat Ini

### Composer

| Package | Current |
| --- | --- |
| PHP | 8.4.17 |
| laravel/framework | v13.11.2 ⬆️ |
| filament/filament | v5.6.5 ⬆️ |
| livewire/livewire | v4.3.0 ⬆️ |
| awcodes/richer-editor | v2.1.1 ⬆️ |
| filament/spatie-laravel-media-library-plugin | v5.6.5 ⬆️ |
| guava/filament-icon-picker | 4.0.0 ⬆️ |
| laravel/ai | v0.7.0 ⬆️ |
| laravel/octane | v2.17.4 |
| laravel/pint | v1.29.1 |
| phpunit/phpunit | 13.1.11 ⬆️ |
| laravel/tinker | v3.0.2 ⬆️ |
| spatie/browsershot | 5.3.0 |
| barryvdh/laravel-dompdf | v3.1.2 |

### NPM

| Package | Current |
| --- | --- |
| vite | ^8.0.14 ⬆️ |
| tailwindcss | 4.3.0 |
| @tailwindcss/vite | 4.3.0 |
| axios | 1.16.1 |
| laravel-vite-plugin | ^3.1.0 ⬆️ |
| concurrently | 9.2.1 |
| puppeteer | 23.11.1 │
| @mermaid-js/mermaid-cli | 11.15.0 |

## Major Upgrade — DONE ✅

All 4 phases completed. No breaking changes detected.

Composer dry-run:

```txt
all_major_exit=0
Lock file operations: 6 installs, 48 updates, 4 removals
```

Major candidates:

| Package | From | To | Status |
| --- | --- | --- | --- |
| laravel/framework | 12.60.2 | **13.11.2** | ✅ Done |
| filament/filament | 4.11.5 | **5.6.5** | ✅ Done |
| livewire/livewire | 3.8.0 | **4.3.0** | ✅ Done |
| filament/spatie-laravel-media-library-plugin | 3.3.30 | **5.6.5** | ✅ Done |
| awcodes/richer-editor | 1.2.2 | **2.1.1** | ✅ Done |
| guava/filament-icon-picker | 3.1.0 | **4.0.0** | ✅ Done |
| laravel/ai | 0.1.5 | **0.7.0** | ✅ Done |
| laravel/tinker | 2.11.1 | **3.0.2** | ✅ Done |
| phpunit/phpunit | 11.5.55 | **13.1.11** | ✅ Done |
| vite | 7.3.3 | **8.0.14** | ✅ Done |
| laravel-vite-plugin | 2.1.0 | **3.1.0** | ✅ Done |
| puppeteer | 23.11.1 | 25.x | ⏳ Skipped (peer dep) |

## Known Blockers / Warnings

### Puppeteer 25 — Skipped ✅

`@mermaid-js/mermaid-cli@11.15.0` has peer dependency:

```txt
puppeteer "^23 || ^24"
```

Puppeteer stays at 23.x. Will upgrade when Mermaid CLI supports 25.

### Filament 5 — Done ✅

All Filament classes verified. No breaking changes:

### Laravel 13 — Done ✅

`bootstrap/app.php` structure compatible. Octane v2 works. AI SDK v0.7 verified.

## Recommended Upgrade Order

### Phase 1 — Frontend Major ✅

Target: `vite ^8`, `laravel-vite-plugin ^3`

Result: Vite 8.0.14, Plugin 3.1.0. Build ✅ Tests ✅

### Phase 2 — PHPUnit Major ✅

Target: `phpunit/phpunit ^13.0`

Result: PHPUnit 13.1.11. No config changes needed. Tests pass.

### Phase 3 — Filament 5 Family ✅

All upgraded. Routes verified. Pint clean. Tests pass.

### Phase 4 — Laravel 13 + AI SDK ✅

All upgraded. Migrations clean. Bootstrap/app.php compatible. Octane v2 stable.

## Skipped

- `puppeteer` 23.11.1 → waiting for `@mermaid-js/mermaid-cli` peer dep support

## Validation Commands

Run after every phase:

```bash
vendor/bin/pint --dirty --format agent
npm run build
php artisan test --compact
composer outdated --direct
npm outdated --depth=0
```

## Notes

Earlier cleanup deleted unused files:

- 9 unused AI agents
- 2 unused services

These deletes are separate from package upgrade work.
