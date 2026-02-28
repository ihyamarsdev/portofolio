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

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.17
- filament/filament (FILAMENT) - v4
- laravel/ai (AI) - v0
- laravel/framework (LARAVEL) - v12
- laravel/octane (OCTANE) - v2
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `tailwindcss-development` — Styles applications using Tailwind CSS v4 utilities. Activates when adding styles, restyling components, working with gradients, spacing, layout, flex, grid, responsive design, dark mode, colors, typography, or borders; or when the user mentions CSS, styling, classes, Tailwind, restyle, hero section, cards, buttons, or any visual/UI changes.
- `developing-with-ai-sdk` — Builds AI agents, generates text and chat responses, produces images, synthesizes audio, transcribes speech, generates vector embeddings, reranks documents, and manages files and vector stores using the Laravel AI SDK (laravel/ai). Supports structured output, streaming, tools, conversation memory, middleware, queueing, broadcasting, and provider failover. Use when building, editing, updating, debugging, or testing any AI functionality, including agents, LLMs, chatbots, text generation, image generation, audio, transcription, embeddings, RAG, similarity search, vector stores, prompting, structured output, or any AI provider (OpenAI, Anthropic, Gemini, Cohere, Groq, xAI, ElevenLabs, Jina, OpenRouter).

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

=== tailwindcss/core rules ===

# Tailwind CSS

- Always use existing Tailwind conventions; check project patterns before adding new ones.
- IMPORTANT: Always use `search-docs` tool for version-specific Tailwind CSS documentation and updated code examples. Never rely on training data.
- IMPORTANT: Activate `tailwindcss-development` every time you're working with a Tailwind CSS or styling-related task.

=== filament/filament rules ===

## Filament

- Filament is used by this application. Follow existing conventions for how and where it's implemented.
- Filament is a Server-Driven UI (SDUI) framework for Laravel that lets you define user interfaces in PHP using structured configuration objects. Built on Livewire, Alpine.js, and Tailwind CSS.
- Use the `search-docs` tool for official documentation on Artisan commands, code examples, testing, relationships, and idiomatic practices.

### Artisan

- Use Filament-specific Artisan commands to create files. Find them with `list-artisan-commands` or `php artisan --help`.
- Inspect required options and always pass `--no-interaction`.

### Patterns

Use static `make()` methods to initialize components. Most configuration methods accept a `Closure` for dynamic values.

Use `Get $get` to read other form field values for conditional logic:

<code-snippet name="Conditional form field" lang="php">
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

Select::make('type')
    ->options(CompanyType::class)
    ->required()
    ->live(),

TextInput::make('company_name')
    ->required()
    ->visible(fn (Get $get): bool => $get('type') === 'business'),

</code-snippet>

Use `state()` with a `Closure` to compute derived column values:

<code-snippet name="Computed table column" lang="php">
use Filament\Tables\Columns\TextColumn;

TextColumn::make('full_name')
    ->state(fn (User $record): string => "{$record->first_name} {$record->last_name}"),

</code-snippet>

Actions encapsulate a button with optional modal form and logic:

<code-snippet name="Action with modal form" lang="php">
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

Action::make('updateEmail')
    ->form([
        TextInput::make('email')->email()->required(),
    ])
    ->action(fn (array $data, User $record): void => $record->update($data)),

</code-snippet>

### Testing

Authenticate before testing panel functionality. Filament uses Livewire, so use `livewire()` or `Livewire::test()`:

<code-snippet name="Filament Table Test" lang="php">
    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->searchTable($users->first()->name)
        ->assertCanSeeTableRecords($users->take(1))
        ->assertCanNotSeeTableRecords($users->skip(1));

</code-snippet>

<code-snippet name="Filament Create Resource Test" lang="php">
    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Test',
            'email' => 'test@example.com',
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(User::class, [
        'name' => 'Test',
        'email' => 'test@example.com',
    ]);

</code-snippet>

<code-snippet name="Testing Validation" lang="php">
    livewire(CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => 'invalid-email',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'email',
        ])
        ->assertNotNotified();

</code-snippet>

<code-snippet name="Calling Actions" lang="php">
    use Filament\Actions\DeleteAction;
    use Filament\Actions\Testing\TestAction;

    livewire(EditUser::class, ['record' => $user->id])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    livewire(ListUsers::class)
        ->callAction(TestAction::make('promote')->table($user), [
            'role' => 'admin',
        ])
        ->assertNotified();

</code-snippet>

### Common Mistakes

**Commonly Incorrect Namespaces:**
- Form fields (TextInput, Select, etc.): `Filament\Forms\Components\`
- Infolist entries (for read-only views) (TextEntry, IconEntry, etc.): `Filament\Infolists\Components\`
- Layout components (Grid, Section, Fieldset, Tabs, Wizard, etc.): `Filament\Schemas\Components\`
- Schema utilities (Get, Set, etc.): `Filament\Schemas\Components\Utilities\`
- Actions: `Filament\Actions\` (no `Filament\Tables\Actions\` etc.)
- Icons: `Filament\Support\Icons\Heroicon` enum (e.g., `Heroicon::PencilSquare`)

**Recent breaking changes to Filament:**
- File visibility is `private` by default. Use `->visibility('public')` for public access.
- `Grid`, `Section`, and `Fieldset` no longer span all columns by default.

=== laravel/ai rules ===

## Laravel AI SDK

- This application uses the Laravel AI SDK (`laravel/ai`) for all AI functionality.
- Activate the `developing-with-ai-sdk` skill when building, editing, updating, debugging, or testing AI agents, text generation, chat, streaming, structured output, tools, image generation, audio, transcription, embeddings, reranking, vector stores, files, conversation memory, or any AI provider integration (OpenAI, Anthropic, Gemini, Cohere, Groq, xAI, ElevenLabs, Jina, OpenRouter).

</laravel-boost-guidelines>
