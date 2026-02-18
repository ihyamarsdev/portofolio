# CV Template Feature

## Profile Model CV Template
- Location: `app/Models/Profile.php`
- Field: `cv_template` (fillable)
- Options: 'ats', 'modern', 'classic', 'minimal'
- Default in form: 'modern'

## CV Download Route
- Route: `cv.download` with query parameter `?template=xxx`
- Controller: `App\Http\Controllers\CVController@download()`
- Default template: 'ats' (if not specified)
- File: `routes/web.php`

## Home Page CV Download Button
- Location: `resources/views/pages/home.blade.php` (line ~99)
- Now uses `$profile->cv_template ?? 'modern'` to get template from profile
- Fallback to 'modern' if cv_template is null

## CV Templates
- Location: `resources/views/cv/`
- Templates: ats.blade.php, modern.blade.php, classic.blade.php, minimal.blade.php
