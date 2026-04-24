<?php

use App\Http\Controllers\Admin\ActionPointController;
use App\Http\Controllers\Admin\ActionPointStatusController;
use App\Http\Controllers\Admin\CriterionController;
use App\Http\Controllers\Admin\CriterionScoreController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\IndicatorController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ReportingPeriodController;
use App\Http\Controllers\Admin\StandardController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Teacher\ActionPointController as TeacherActionPointController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TeamController as TeacherTeamController;
use App\Http\Controllers\Teacher\ThemeController as TeacherThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'role:ok_medewerker'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:ok_medewerker'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('teams', TeamController::class);
    Route::get('teams/{team}/members', [TeamController::class, 'members'])->name('teams.members');
    Route::resource('locations', LocationController::class)->except(['show']);
    Route::resource('themes', ThemeController::class)->except(['show']);
    Route::resource('standards', StandardController::class)->except(['show']);
    Route::resource('criteria', CriterionController::class)->except(['show']);
    Route::resource('indicators', IndicatorController::class)->except(['show']);
    Route::resource('action-point-statuses', ActionPointStatusController::class)->except(['show']);
    Route::resource('reporting-periods', ReportingPeriodController::class)->except(['show']);
    Route::resource('action-points', ActionPointController::class)->except(['show']);
    Route::resource('criterion-scores', CriterionScoreController::class)->except(['show']);
    Route::resource('evaluations', EvaluationController::class)->except(['show']);
});

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified', 'role:ok_medewerker|kwaliteitszorg|onderwijsleider|medewerker|directie'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/themes', [TeacherThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/{theme}', [TeacherThemeController::class, 'show'])->name('themes.show');
    Route::get('/team', [TeacherTeamController::class, 'index'])->name('team.index');
    Route::get('/action-points', [TeacherActionPointController::class, 'index'])->name('action-points.index');
});
