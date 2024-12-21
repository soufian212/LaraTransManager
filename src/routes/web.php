<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Soufian212\LaraTransManager\Http\Controller\DashboardController;
use Soufian212\LaraTransManager\Http\Middleware\HandleInertiaRequests;


Route::middleware(['web', HandleInertiaRequests::class])->group(function () {
    Route::get('/translations/clearAllCache', [DashboardController::class, 'clearAllCache'])->name('ltm.translations.clearAllCache');


    Route::get('/translations', [DashboardController::class, 'index'])->name('ltm.translations');
    Route::post('/translations/{language}/new', [DashboardController::class, 'store'])->name('ltm.translations.store');
    Route::delete('/translations/{language}/delete', [DashboardController::class, 'destroy'])->name('ltm.translations.destroy');
    Route::get('/translations/{language}/show', [DashboardController::class, 'show'])->name('ltm.translations.show');
    Route::get('/translations/{language}/edit/{phraseId}', [DashboardController::class, 'edit'])->name('ltm.translations.edit');
    Route::put('/translations/update/{phraseId}', [DashboardController::class, 'update'])->name('ltm.translations.update');
    Route::get('/translations/source', [DashboardController::class, 'sourceShow'])->name('ltm.translations.sourceShow');
    Route::get('/translations/source/{phraseId}/edit', [DashboardController::class, 'sourceEdit'])->name('ltm.translations.sourceEdit');
    Route::get('/translations/source/create', [DashboardController::class, 'sourceCreate'])->name('ltm.translations.sourceCreate');
    Route::post('/translations/source/create', [DashboardController::class, 'sourceStore'])->name('ltm.translations.sourceStore');
    Route::put('/translations/source/{phraseId}/edit', [DashboardController::class, 'sourceUpdate'])->name('ltm.translations.sourceUpdate');
    Route::delete('/translations/source/{phraseId}/delete', [DashboardController::class, 'sourceDestroy'])->name('ltm.translations.sourceDelete');
});
