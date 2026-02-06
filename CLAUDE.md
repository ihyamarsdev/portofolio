# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 portfolio website for a DevOps Engineer, built with Filament v4 as the admin panel. The site includes:
- Public portfolio pages (home, projects, about, certificates, blog, contact)
- Filament admin panel for content management
- CV Generator with AI-powered ATS analysis using Google Gemini API
- Job description matching for targeted applications
- Multiple CV templates (ATS, Modern, Classic, Minimal)
- Multiple export formats (PDF, DOCX, Text, Markdown)
- Media management via Spatie Media Library
- PDF generation via DomPDF

## Common Commands

### Development
```bash
# Start all development services (server, queue, logs, vite)
composer dev

# Install and setup everything from scratch
composer setup

# Start only the Laravel server
php artisan serve

# Start Vite dev server
npm run dev

# Build assets for production
npm run build
```

### Testing
```bash
# Run all tests
composer test
# or
php artisan test

# Run PHPUnit directly
./vendor/bin/phpunit
```

### Database
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Create a new migration
php artisan make:migration create_table_name
```

### Filament
```bash
# Create a new Filament resource
php artisan make:filament-resource ResourceName

# Create a new Filament page
php artisan make:filament-page PageName

# Create a new Filament widget
php artisan make:filament-widget WidgetName
```

### Code Quality
```bash
# Laravel Pint (code formatting)
./vendor/bin/pint

# Check for issues without fixing
./vendor/bin/pint --test
```

## Architecture

### Directory Structure
```
app/
├── Filament/           # Admin panel components
│   ├── Pages/         # Custom pages (e.g., CVGenerator)
│   ├── Resources/     # CRUD resources for models
│   └── Widgets/       # Dashboard widgets
├── Http/Controllers/  # Public page controllers
├── Models/           # Eloquent models (many use Spatie MediaLibrary)
├── Providers/        # Service providers
│   └── Filament/     # Admin panel configuration
└── Services/         # Business logic (e.g., GeminiService)
```

### Key Models and Relationships

- **Profile**: Personal info, with `is_primary` flag for the main profile
- **HomeSettings**: Singleton model for site-wide configuration (hero section, CTA, etc.)
- **Project**: Portfolio projects with media collections (images, diagrams)
- **Post**: Blog posts with publish status
- **Skill**, **Stack**, **Certificate**, **Education**, **Experience**: CV-related data
- **Inbox**: Contact form submissions
- **ServiceStatus**: Infrastructure status monitoring

### Services

- **GeminiService**: Integrates with Google Gemini API for CV/ATS analysis
  - Uses `gemini-3-flash-preview` model
  - Requires `GEMINI_API_KEY` in `.env`
  - Returns JSON with ATS score, keywords, format score, suggestions
  - Methods: `analyzeCV()`, `matchAgainstJob()`, `comprehensiveCVAnalysis()`, `analyzeKeywords()`, `analyzeActionVerbs()`, `validateATSFormat()`, `getImprovementSuggestions()`

- **JobMatchService**: Parses job descriptions and matches CV against requirements
  - Extracts required skills, keywords, responsibilities from job descriptions
  - Calculates match percentage between CV and job requirements
  - Methods: `parseJobDescription()`, `extractKeywords()`, `extractSkills()`, `calculateMatchScore()`, `generateGapAnalysis()`, `suggestAdditions()`

- **TemplateService**: Manages CV templates
  - Returns available templates from config
  - Validates template selection
  - Methods: `getAvailableTemplates()`, `getTemplatePreview()`, `validateTemplate()`, `applyTemplate()`

- **CVGeneratorService**: Generates CVs in multiple formats
  - Supports PDF (DomPDF), DOCX (PhpOffice\PhpWord), Text, Markdown
  - Methods: `generatePDF()`, `generateDOCX()`, `generateText()`, `generateMarkdown()`, `buildCVContent()`

### Frontend Architecture

- **Tailwind CSS v4**: Used via `@tailwindcss/vite` plugin
- **Vite**: Build tool via `laravel-vite-plugin`
- **Filament**: Admin panel at `/admin` path with amber primary color
- **Custom CSS**: Additional styles in `public/css/filament.css` injected via render hook

## CV Analysis & Generator

### Features
- AI-powered ATS analysis using Google Gemini API
- Job description matching for targeted applications
- Multiple CV templates (ATS, Modern, Classic, Minimal)
- Multiple export formats (PDF, DOCX, Text, Markdown)
- Live preview functionality
- Analysis history tracking

### Usage

1. Set GEMINI_API_KEY in .env
   ```env
   GEMINI_API_KEY=your_gemini_api_key_here
   ```

2. Go to Admin Panel > CV Management > CV Generator & Analysis

3. Click "Analyze CV" for ATS analysis
4. Paste job description and click "Match Job" for matching
5. Select template from options
6. Download CV in desired format

### Routes
- `/cv/download` - Download CV as PDF
- `/cv/download/docx` - Download CV as DOCX
- `/cv/download/text` - Download CV as plain text
- `/cv/download/markdown` - Download CV as Markdown
- `/cv/preview` - Live CV preview

### Models
- **CVAnalysis**: Stores analysis results
  - `ats_score`: ATS compatibility score (0-100)
  - `format_score`: Formatting score (0-100)
  - `job_match_score`: Job matching score (0-100)
  - `keywords_found`: Array of found keywords
  - `keywords_missing`: Array of missing keywords
  - `suggestions`: Array of improvement suggestions
  - `industry_match`: Industry compatibility scores
  - Relationships: `belongsTo(Profile)`, `hasMany(Profile)`

- **Profile** (updated):
  - `cv_template`: Selected template (ats, modern, classic, minimal)
  - `cvAnalyses()`: Has many CV analyses
  - `latestAnalysis()`: Get most recent analysis

### CV Templates
Located in `resources/views/cv/`:
- **ats.blade.php**: ATS-optimized, clean and simple design
- **modern.blade.php**: Contemporary design with visual hierarchy, gradient header
- **classic.blade.php**: Traditional conservative layout with serif fonts
- **minimal.blade.php**: Minimalist design with whitespace and clean typography

### Admin Panel Configuration

Located in `app/Providers/Filament/AdminPanelProvider.php`:
- Panel ID: `admin`
- Path: `/admin`
- Primary color: Amber
- Custom CSS injected via `panels::head.end` render hook
- Auto-discovers Resources, Pages, and Widgets from `app/Filament/`

## Important Notes

### Media Handling
Many models (Project, Certificate, etc.) use Spatie MediaLibrary:
```php
$model->addMedia($file)->toCollection('collection-name');
$model->getFirstMediaUrl('collection-name');
```

### HomeSettings Pattern
`HomeSettings::getSettings()` uses a singleton pattern - it returns the first record or creates one with defaults. Always use this method instead of `HomeSettings::first()`.

### Filament Resources
Resources are auto-discovered from `app/Filament/Resources/`. Each resource typically has:
- `Pages/ListRecords`: Listing view
- `Pages/CreateRecord`: Creation form
- `Pages/EditRecord`: Edit form

### Testing
- PHPUnit configured for both Unit and Feature tests
- Uses SQLite in-memory database for tests
- Test files in `tests/Unit/` and `tests/Feature/`

### Environment Variables Required
- Standard Laravel variables (DB_*, APP_*, etc.)
- `GEMINI_API_KEY`: For CV analysis feature
