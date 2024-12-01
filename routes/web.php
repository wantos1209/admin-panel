<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubareaController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\UserapkController;
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

Route::get('/', function () {
    return redirect()->route('pengirimans.index');
})->Middleware('auth');

Route::get('/login', [LoginController::class, 'index'])->name('login')->Middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->Middleware('auth')->name('logout');


/* Middleware */

Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pengirimans', PengirimanController::class);
    Route::get('/pengirimans/{id}/details', [PengirimanController::class, 'getDetails']);
    Route::get('/pengirimans/{id}/export', [PengirimanController::class, 'export'])->name('pengirimans.export');
    Route::resource('users', UserController::class);
    Route::get('/changepassword/{id}', [UserController::class, 'editPassword'])->name('users.editPassword');
    Route::post('/changepassword/{id}', [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::resource('areas', AreaController::class);
    Route::resource('subareas', SubareaController::class);
    Route::resource('userapks', UserapkController::class);
});
