<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// SEO & Language Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Public Pages
Route::get('/cv/download', [CVController::class, 'download'])->name('cv.download');
Route::get('/cv/download/docx', [CVController::class, 'downloadDOCX'])->name('cv.download.docx');
Route::get('/cv/download/text', [CVController::class, 'downloadText'])->name('cv.download.text');
Route::get('/cv/download/markdown', [CVController::class, 'downloadMarkdown'])->name('cv.download.markdown');
Route::get('/cv/preview', [CVController::class, 'preview'])->name('cv.preview');
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/projects', [PageController::class, 'projects'])->name('projects');
Route::get('/projects/{slug}', [PageController::class, 'projectShow'])->name('projects.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/certificates', [PageController::class, 'certificates'])->name('certificates');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogShow'])->name('blog.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1') // Limit to 5 requests per minute per IP
    ->name('contact.store');
