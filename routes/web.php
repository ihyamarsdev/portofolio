<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/cv/download', [CVController::class, 'download'])->name('cv.download');
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/projects', [PageController::class, 'projects'])->name('projects');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/certificates', [PageController::class, 'certificates'])->name('certificates');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogShow'])->name('blog.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
