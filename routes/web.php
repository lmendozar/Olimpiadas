<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GameTypeController;
use App\Http\Controllers\Admin\AllianceController;
use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Admin\CompetitionController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/match/{matchRecord}', [DashboardController::class, 'showMatch'])->name('match.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes (Only for Organizers)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_organizer'])->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggle-role');
    
    // Game Types
    Route::resource('game-types', GameTypeController::class);
    
    // Alliances
    Route::resource('alliances', AllianceController::class);
    
    // People
    Route::resource('people', PersonController::class);
    
    // Competitions
    Route::resource('competitions', CompetitionController::class);
    Route::post('competitions/{competition}/finalize', [CompetitionController::class, 'finalize'])
        ->name('competitions.finalize');
    
    // Matches
    Route::resource('matches', MatchController::class);
    Route::post('matches/{matchPlay}/finalize', [MatchController::class, 'finalize'])
        ->name('matches.finalize');
    
    // Export Routes
    Route::get('export/rankings', [ExportController::class, 'exportRankings'])->name('export.rankings');
    Route::get('export/competitions', [ExportController::class, 'exportCompetitions'])->name('export.competitions');
    Route::get('export/matches', [ExportController::class, 'exportMatches'])->name('export.matches');
    
    // System Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/reset', [SettingsController::class, 'reset'])->name('settings.reset');
});

