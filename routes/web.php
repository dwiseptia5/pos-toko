<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KasirController;


/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Halaman yang harus login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return redirect()->route('admin.index');
    });

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.index');

    Route::resource('products', ProductController::class);


    /*
    |--------------------------------------------------------------------------
    | Kasir
    |--------------------------------------------------------------------------
    */

    Route::get('/kasir', [KasirController::class, 'index'])
        ->name('kasir.index');

    Route::post('/kasir/transaksi', [KasirController::class, 'store'])
        ->name('kasir.store');

    Route::get('/kasir/struk/{id}', [KasirController::class, 'struk'])
        ->name('kasir.struk');

});