# Portofolio

Portfolio website and admin CMS for a DevOps engineer, built with Laravel 13 and Filament 5.

## Features

- Public pages:
  - Home
  - Projects
  - About
  - Certificates
  - Blog
  - Contact
- Filament admin panel for content management
- CV generator with exports:
  - PDF
  - DOCX
  - Text
  - Markdown
- AI-assisted CV analysis via Laravel AI using a custom OpenAI-compatible `cv` provider
- AI-assisted helpers for:
  - post SEO metadata generation
  - project architecture diagram generation
- Mermaid diagram rendering
- Media management with Spatie Media Library
- Docker deployment with FrankenPHP, Postgres, Dragonfly, and Traefik

## Stack

Backend:
- PHP 8.4
- Laravel 13
- Filament 5
- Laravel AI
- Laravel Octane
- PostgreSQL
- Dragonfly / Redis-compatible cache

Frontend / tooling:
- Vite 8
- Tailwind CSS 4
- Puppeteer
- Mermaid CLI

Documents / export:
- DomPDF
- PhpWord
- Browsershot

## Project structure

Key directories:
- `app/Filament` — admin panel pages/resources/widgets
- `app/Http/Controllers` — public web controllers
- `app/Models` — domain models
- `app/Services` — app services such as CV generation and diagram rendering
- `app/Ai` — AI agents and custom gateway integration
- `resources/views` — Blade views for public pages, admin pages, and CV templates
- `routes/web.php` — public routes
- `docker/` — Dockerfile, compose file, entrypoint

## Main routes

Public routes:
- `/`
- `/projects`
- `/projects/{slug}`
- `/about`
- `/certificates`
- `/blog`
- `/blog/{slug}`
- `/contact`

CV routes:
- `/cv/download`
- `/cv/download/docx`
- `/cv/download/text`
- `/cv/download/markdown`
- `/cv/preview`

Admin panel:
- `/admin`

## Requirements

Minimum practical requirements:
- PHP 8.4+
- Composer
- Node.js + npm
- PostgreSQL
- Redis-compatible cache if using the production-style stack
- Chromium runtime libraries if you want Browsershot fallback inside containers

Optional but used by some features:
- Chromium / Puppeteer runtime for Browsershot PDF rendering
- Mermaid CLI for architecture diagram rendering

## Local setup

### 1. Clone and enter the project
```bash
git clone <your-repo-url>
cd portofolio
```

### 2. Install backend dependencies
```bash
composer install
```

### 3. Install frontend dependencies
```bash
npm install
```

### 4. Create environment file
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure database and app settings
Edit `.env` as needed.

Important environment variables include:
```env
APP_NAME=Portofolio
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8888

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=portofolio
DB_USERNAME=postgres
DB_PASSWORD=postgres

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

CV_BASE_URL=
CV_API_KEY=
CV_MODEL=cv
```

### 6. Run migrations
```bash
php artisan migrate
```

### 7. Start development environment
```bash
composer dev
```

This starts:
- Laravel dev server on `0.0.0.0:8888`
- queue listener
- Laravel Pail log viewer
- Vite dev server

## One-command setup

The project provides:
```bash
composer setup
```

This runs:
- `composer install`
- copy `.env` if missing
- `php artisan key:generate`
- `php artisan migrate --force`
- `npm install`
- `npm run build`

## Testing

Run all tests:
```bash
composer test
```

Or directly:
```bash
php artisan test
```

Formatting:
```bash
./vendor/bin/pint
./vendor/bin/pint --test
```

## AI configuration

This project uses Laravel AI with a custom OpenAI-compatible provider named `cv`.

Relevant files:
- `config/ai.php`
- `config/cv.php`
- `app/Ai/Gateway/CvOpenAiCompatibleGateway.php`
- `app/Ai/Agents/ComprehensiveCVAnalysisAgent.php`
- `app/Ai/Agents/PostSeoAgent.php`
- `app/Ai/Agents/ArchitectureDiagramAgent.php`

Required environment variables for the custom provider:
```env
CV_BASE_URL=
CV_API_KEY=
CV_MODEL=cv
```

AI-backed features currently include:
- CV analysis
- post SEO metadata generation
- project architecture diagram generation

## CV generation

Main files:
- `app/Http/Controllers/CVController.php`
- `app/Services/CVGeneratorService.php`
- `resources/views/cv/`
- `config/cv-templates.php`

Available templates:
- `ats`
- `modern`
- `classic`
- `minimal`

Behavior:
- PDF generation tries Browsershot first
- if Browsershot fails, falls back to DomPDF
- DOCX export uses PhpWord
- text and markdown exports are generated in service code

## Environments and deployment (authoritative)

This repository has **three distinct run paths**. They are not interchangeable —
`scripts/deploy.sh` is decoupled from both compose files, so changing a compose
file never changes a live deploy.

| Path | Authoritative files | Used for | Invoked by |
|---|---|---|---|
| Local, bare metal (default) | `composer dev` / `composer setup` | everyday development on host PHP | `composer dev` (serve :8888, queue, Pail, Vite) |
| Local, containers (optional) | root `compose.yaml` | Laravel Sail dev (port 80, bind mount, Xdebug/Vite) | `./vendor/bin/sail up` |
| Production, containers | `docker/docker-compose.yml` + `docker/Dockerfile` + `docker/Dockerfile.base` | FrankenPHP + Postgres + Dragonfly + Traefik (port 8888) | documented command below |
| Production, live (bare metal) | `scripts/deploy.sh` → systemd `portofolio-web`, `portofolio-queue` | the actual live deploy | `./scripts/deploy.sh` |

### Which compose file should I use?

- `compose.yaml` (root) is the **Laravel Sail** file for local development only.
  It builds from `vendor/laravel/sail/runtimes/8.4`, mounts the repo, and exposes
  port 80 + Vite 5173. It has no database or cache service.
- `docker/docker-compose.yml` is the **production container** stack. It runs the
  app image built by `docker/Dockerfile` on port 8888 with Postgres and Dragonfly,
  and expects an external network named `backend`.

Do not merge them: they share no service names, networks, or Dockerfiles.
`scripts/deploy.sh` uses neither — it deploys to host systemd services, so the
container stack is only used when you opt into it.

### Web server rewrites (.htaccess)

- `public/.htaccess` is the authoritative Laravel front-controller rewrite
  (header forwarding, trailing-slash redirect, dispatch to `index.php`).
- Root `.htaccess` is a legacy shared-hosting fallback: it forwards requests
  into `public/` when Apache's `DocumentRoot` points at the project root.
  It is harmless when `DocumentRoot` is `public/` or when Caddy/FrankenPHP serves
  the app (Caddyfile handles rewrites; neither `.htaccess` is read).

## Docker deployment

Files:
- `docker/Dockerfile`
- `docker/Dockerfile.base` (build once: `docker/build-base.sh 8.4`, consumed as `PHP_BASE_IMAGE`)
- `docker/docker-compose.yml`
- `docker/docker-entrypoint.sh`

Services in compose:
- `portofolio` app
- `db` (Postgres)
- `redis` (Dragonfly)

Reverse proxy:
- Traefik labels are included for HTTPS routing

Important note:
- Compose expects an external Docker network named `backend`

Create it if missing:
```bash
docker network create backend
```

### Start with Docker Compose
From the `docker/` directory:
```bash
docker compose up -d --build
```

### Bare-metal deploy (live production)
`scripts/deploy.sh` performs a host-native deploy: fast-forward merge, conditional
`composer install`, `npm ci && npm run build`, `php artisan migrate --force`,
cache/route/view/component warming, `storage:link`, then restarts the systemd
units `portofolio-web` and `portofolio-queue` and checks `http://127.0.0.1:8888/`.
It never invokes Docker; the queue worker exists only as a systemd unit, not as a
compose service.

## Admin content model

Important models:
- `Profile`
- `HomeSettings`
- `Project`
- `Post`
- `Category`
- `Skill`
- `Stack`
- `Certificate`
- `Education`
- `Experience`
- `Inbox`
- `CVAnalysis`
- `ServiceStatus`

Important behavior:
- `Profile` uses `is_primary` to mark the primary profile
- `HomeSettings::getSettings()` acts like a singleton settings record
- `Post::published()` controls public blog visibility

## Operational notes

- `README.md` should reflect actual code/config, not framework defaults
- AI calls should use the configured `cv` provider/model instead of hardcoded provider-specific model names
- some CV-related code paths still need careful review for profile scoping consistency if multi-profile data is introduced
- deployment may need dedicated queue/scheduler containers depending on production usage

## Useful commands

Development:
```bash
composer dev
php artisan serve
npm run dev
```

Build:
```bash
npm run build
```

Database:
```bash
php artisan migrate
php artisan migrate:fresh --seed
```

Testing:
```bash
composer test
php artisan test
```

Formatting:
```bash
./vendor/bin/pint
./vendor/bin/pint --test
```

## License

This project is application code for a personal portfolio site. Add or adjust licensing terms here if needed.
