<?php

use App\Http\Controllers\AdminAuth\AuthenticatedSessionController as AdminAuthSessionController;
use App\Http\Controllers\admin\AdvocateController;
use App\Http\Controllers\admin\CaseController;
use App\Http\Controllers\admin\CaseDivisionController;
use App\Http\Controllers\admin\CaseTypeController;
use App\Http\Controllers\admin\CourtController;
use App\Http\Controllers\admin\DateController;
use App\Http\Controllers\admin\FilterController;
use App\Http\Controllers\admin\HistoryController;
use App\Http\Controllers\admin\MonthController;
use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard_old', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard_old');

Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class)->names('dashboard.roles');
    Route::resource('users', UsersController::class)->names('dashboard.users');
    Route::resource('admins', AdminsController::class)->names('dashboard.admins');

    Route::get('/login', [AdminAuthSessionController::class, 'showLoginForm'])->name('dashboard.login');
    Route::post('/login/submit', [AdminAuthSessionController::class, 'login'])->name('dashboard.login.submit');

    Route::post('/logout/submit', [AdminAuthSessionController::class, 'logout'])->name('dashboard.logout.submit');

    //Case
    Route::resource('types', CaseTypeController::class)->names('dashboard.types');
    Route::resource('divisions', CaseDivisionController::class)->names('dashboard.divisions');
    Route::resource('courts', CourtController::class)->names('dashboard.courts');
    Route::resource('cases', CaseController::class)->names('dashboard.cases');
    Route::resource('projects', ProjectController::class)->names('dashboard.projects');
    Route::resource('advocates', AdvocateController::class)->names('dashboard.advocates');
    Route::resource('histories', HistoryController::class)->names('dashboard.histories');

    Route::resource('reports/filter', FilterController::class)->names('dashboard.reports.filter');
    Route::resource('reports/date', DateController::class)->names('dashboard.reports.date');
    Route::post('/reports/date', [DateController::class, 'DateRange'])->name('dashboard.reports.date-range');
    Route::get('/reports/month', [MonthController::class, 'index'])->name('dashboard.reports.month');
    Route::get('/reports/monthprevious', [MonthController::class, 'Previous'])->name('dashboard.reports.monthprevious');
});

require __DIR__ . '/auth.php';
