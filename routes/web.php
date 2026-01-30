<?php

use App\Http\Controllers\Admin\CriterionController;
use App\Http\Controllers\Admin\IndicatorController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\StandardController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('teams', TeamController::class);
    Route::get('teams/{team}/members', [TeamController::class, 'members'])->name('teams.members');
    Route::resource('locations', LocationController::class)->except(['show']);
    Route::resource('themes', ThemeController::class)->except(['show']);
    Route::resource('standards', StandardController::class)->except(['show']);
    Route::resource('criteria', CriterionController::class)->except(['show']);
    Route::resource('indicators', IndicatorController::class)->except(['show']);
});

require __DIR__.'/settings.php';
