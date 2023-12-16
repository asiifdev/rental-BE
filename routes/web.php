<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//This Route For URL use with Logs
// Route::middleware(['writeVisitor'])->group(function () {
// });


Auth::routes();

Route::get('/home', function () {
    return redirect()->to('admin');
});

Route::get('/', function () {
    return redirect()->to('admin');
});

$user = auth()->user();

Route::group(["as" => "admin.", "prefix" => "admin", "middleware" => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(["as" => "master.", "prefix" => "master", "middleware" => ['role:Super Admin|admin']], function () {
        Route::resource('roles', RolesController::class);
        Route::resource('users', UserController::class);
    });
    Route::group(["as" => "settings.", "prefix" => "settings", "middleware" => ['auth']], function () {
        Route::resource('app-setting', AppSettingController::class)->middleware('role:Super Admin|admin');
        Route::resource('company', CompanySettingController::class)->middleware('role:Super Admin|admin');
        Route::resource('my-profile', UserSettingController::class)->middleware('auth');
    });
});
