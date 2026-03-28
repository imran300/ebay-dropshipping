<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrameworkMisuseDemoController;
use App\Http\Controllers\BuggyAnnouncementController;
use App\Http\Controllers\BuggyUserExportController;
use App\Http\Controllers\BuggyUserReportController;
use App\Http\Controllers\BuggyUserSearchController;
use App\Http\Controllers\RiskyProfileUpdateDemoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/framework-misuse-demo', [FrameworkMisuseDemoController::class, 'index'])->name('framework-misuse-demo.index');
    Route::get('/buggy-announcement-demo', [BuggyAnnouncementController::class, 'index'])->name('buggy-announcement-demo.index');
    Route::get('/buggy-user-export-demo', [BuggyUserExportController::class, 'index'])->name('buggy-user-export-demo.index');
    Route::get('/buggy-user-report-demo', [BuggyUserReportController::class, 'index'])->name('buggy-user-report-demo.index');
    Route::get('/buggy-user-search-demo', [BuggyUserSearchController::class, 'index'])->name('buggy-user-search-demo.index');
    Route::patch('/profile/risky-update-demo', [RiskyProfileUpdateDemoController::class, 'update'])->name('profile.risky-update-demo');
});

require __DIR__.'/auth.php';
