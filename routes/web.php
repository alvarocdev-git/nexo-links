<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ClickRedirectController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\SocialLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::view('/help', 'help')->name('help');

Route::get('/l/{link}', ClickRedirectController::class)->name('link.visit');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [LinkController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/design', [DesignController::class, 'edit'])->name('design.edit');
    Route::get('/qr', QrCodeController::class)->name('qr.show');
    Route::patch('/design', [DesignController::class, 'update'])->name('design.update');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::patch('/links/reorder', [LinkController::class, 'reorder'])->name('links.reorder');
    Route::patch('/links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::post('/socials', [SocialLinkController::class, 'store'])->name('socials.store');
    Route::delete('/socials/{socialLink}', [SocialLinkController::class, 'destroy'])->name('socials.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Catch-all public page route: must stay last so it never shadows app routes.
Route::get('/{username}', [PublicPageController::class, 'show'])
    ->where('username', '[a-z0-9]+(?:[-_][a-z0-9]+)*')
    ->name('page.show');
