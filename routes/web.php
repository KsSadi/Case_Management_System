<?php

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
    Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('dashboard');
    Route::resource('roles', 'App\Http\Controllers\RolesController', ['names' => 'dashboard.roles']);
    Route::resource('users', 'App\Http\Controllers\UsersController', ['names' => 'dashboard.users']);
    Route::resource('admins', 'App\Http\Controllers\AdminsController', ['names' => 'dashboard.admins']);

    Route::get('/login', 'App\Http\Controllers\AdminAuth\AuthenticatedSessionController@showLoginForm')->name('dashboard.login');
    Route::post('/login/submit', 'App\Http\Controllers\AdminAuth\AuthenticatedSessionController@login')->name('dashboard.login.submit');

    Route::post('/logout/submit', 'App\Http\Controllers\AdminAuth\AuthenticatedSessionController@logout')->name('dashboard.logout.submit');

    // Route::get('/password/reset', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('dashboard.login');
    // Route::post('/login/submit', 'App\Http\Controllers\Auth\LoginController@login')->name('dashboard.login.submit');

    //Admin Modules
    Route::resource('branches', 'App\Http\Controllers\admin\BranchController', ['names' => 'dashboard.branches']);
    Route::resource('routes', 'App\Http\Controllers\admin\RouteController', ['names' => 'dashboard.routes']);

    //Case
    Route::resource('types', 'App\Http\Controllers\admin\CaseTypeController', ['names' => 'dashboard.types']);
    Route::resource('divisions', 'App\Http\Controllers\admin\CaseDivisionController', ['names' => 'dashboard.divisions']);
    Route::resource('courts', 'App\Http\Controllers\admin\CourtController', ['names' => 'dashboard.courts']);
    Route::resource('cases', 'App\Http\Controllers\admin\CaseController', ['names' => 'dashboard.cases']);
    Route::resource('projects', 'App\Http\Controllers\admin\ProjectController', ['names' => 'dashboard.projects']);
    Route::resource('advocates', 'App\Http\Controllers\admin\AdvocateController', ['names' => 'dashboard.advocates']);
    Route::resource('histories', 'App\Http\Controllers\admin\HistoryController', ['names' => 'dashboard.histories']);

    Route::resource('reports/filter', 'App\Http\Controllers\admin\FilterController', ['names' => 'dashboard.reports.filter']);
    Route::resource('reports/date', 'App\Http\Controllers\admin\DateController', ['names' => 'dashboard.reports.date']);
    Route::post('/reports/date', 'App\Http\Controllers\admin\DateController@DateRange')->name('dashboard.reports.date-range');
    Route::get('/reports/month', 'App\Http\Controllers\admin\MonthController@index')->name('dashboard.reports.month');
    Route::get('/reports/monthprevious', 'App\Http\Controllers\admin\MonthController@Previous')->name('dashboard.reports.monthprevious');



});

require __DIR__ . '/auth.php';
