# AGENT.md

## Project overview

This repository is a Laravel 13 portfolio website and admin CMS for a DevOps engineer.

Main capabilities:
- Public portfolio pages: home, projects, about, certificates, blog, contact
- Filament 5 admin panel for content management
- CV generator with export formats: PDF, DOCX, text, markdown
- AI-assisted CV analysis using Laravel AI with a custom OpenAI-compatible provider (`cv`)
- Mermaid diagram rendering for project architecture assets
- Media handling via Spatie Media Library
- Docker deployment via FrankenPHP, Postgres, Dragonfly/Redis, and Traefik

Repository path:
- `/home/ihya/project/portofolio`

## Stack

Backend:
- PHP 8.4
- Laravel 13
- Filament 5
- Laravel AI
- Laravel Octane
- Postgres
- Redis-compatible cache (Dragonfly in Docker compose)

Frontend / tooling:
- Vite 8
- Tailwind CSS 4
- Puppeteer
- Mermaid CLI (`mmdc`)

Documents / export:
- DomPDF
- PhpWord
- Browsershot

## Important commands

### Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Or use the project script:
```bash
composer setup
```

### Development
```bash
composer dev
```
This starts:
- Laravel dev server on `0.0.0.0:8888`
- queue listener
- Laravel Pail logs
- Vite dev server

### Tests
```bash
composer test
# or
php artisan test
```

### Formatting
```bash
./vendor/bin/pint
./vendor/bin/pint --test
```

### Production build assets
```bash
npm run build
```

## Public routes

Defined in `routes/web.php`:
- `/`
- `/projects`
- `/projects/{slug}`
- `/about`
- `/certificates`
- `/blog`
- `/blog/{slug}`
- `/contact`
- `/cv/download`
- `/cv/download/docx`
- `/cv/download/text`
- `/cv/download/markdown`
- `/cv/preview`

## Admin panel

Filament panel provider:
- `app/Providers/Filament/AdminPanelProvider.php`

Panel details:
- path: `/admin`
- panel id: `admin`
- custom theme: `resources/css/filament/admin/theme.css`

Main resources include:
- Posts
- Projects
- Skills
- Stacks
- Categories
- Certificates
- Education
- Experiences
- Inboxes
- HomeSettings
- CVAnalyses

Custom pages include:
- `App\Filament\Pages\ManageProfile`
- `App\Filament\Pages\CVGenerator`

## Core models

Important models under `app/Models`:
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

Key patterns:
- `Profile` uses `is_primary` to identify the main profile.
- `HomeSettings::getSettings()` acts like a singleton config record.
- `Post::published()` gates blog visibility by `status` + `published_at`.

## AI integration

AI CV analysis is centered around:
- `app/Filament/Pages/CVGenerator.php`
- `app/Ai/Agents/ComprehensiveCVAnalysisAgent.php`
- `app/Ai/Gateway/CvOpenAiCompatibleGateway.php`
- `config/ai.php`
- `config/cv.php`

Important notes:
- Default AI provider is `cv`
- The `cv` provider uses a custom OpenAI-compatible gateway
- Required env vars may include:
  - `CV_BASE_URL`
  - `CV_API_KEY`
  - `CV_MODEL`
- AI configuration should use `CV_BASE_URL`, `CV_API_KEY`, and `CV_MODEL`

## CV generation

Main files:
- `app/Http/Controllers/CVController.php`
- `app/Services/CVGeneratorService.php`
- `resources/views/cv/`
- `config/cv-templates.php`

Behavior:
- PDF generation tries Browsershot first, then falls back to DomPDF
- DOCX generation uses PhpWord
- text/markdown exports are generated in service code
- template names currently include: `ats`, `modern`, `classic`, `minimal`

## Diagram rendering

Architecture diagram rendering lives in:
- `app/Services/DiagramRenderer.php`

Assumptions:
- `node_modules/.bin/mmdc` exists
- Node runtime is available
- `storage/app/temp` is writable

## Contact flow

Contact form flow:
- controller: `app/Http/Controllers/ContactController.php`
- request validation: `app/Http/Requests/StoreInboxRequest.php`
- model: `App\Models\Inbox`

Protection:
- route throttle: 5 requests / minute / IP

## Docker / deployment

Deployment files:
- `docker/Dockerfile`
- `docker/docker-compose.yml`
- `docker/docker-entrypoint.sh`

Runtime model:
- app served by FrankenPHP on port `8888`
- Postgres database
- Dragonfly as Redis-compatible service
- Traefik reverse proxy with HTTPS labels
- compose uses external Docker network: `backend`

Entrypoint behavior:
- waits for DB and Redis
- production only: runs migrations
- always runs `storage:link`, `config:cache`, `route:cache`, `view:cache`
- starts FrankenPHP

## Known caveats

These are important when modifying the app:

1. Profile scoping is inconsistent in CV-related code.
   - Some methods scope experiences/education to `profile_id`
   - Others query all records globally
   - Be careful not to mix data across profiles

2. Public CV download route currently accepts template names from query params.
   - Validate against `config('cv-templates')` if touching that area

3. Docker compose inspected so far does not define a dedicated scheduler or queue worker service.
   - If background jobs are required in deployment, add them explicitly

4. `README.md` is still the default Laravel README and is not source of truth for this project

## Guidance for agents

When working in this repo:
- prefer actual code over old docs
- verify assumptions against `composer.json`, `routes/web.php`, `config/*.php`, and `app/*`
- keep changes aligned with Laravel 13 + Filament 5
- do not assume provider-specific AI behavior unless verified in code and config
- mention any files changed, commands run, verification performed, and remaining risks in your report
