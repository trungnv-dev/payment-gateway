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

Route::group(['prefix' => 'payment', 'middleware' => 'auth'], function () {
    Route::get('/paypal', [\App\Http\Controllers\PaymentController::class, 'paypal']);
    Route::get('/payjp', [\App\Http\Controllers\PaymentController::class, 'payjp']);
    Route::post('/payjp', [\App\Http\Controllers\PaymentController::class, 'paymentPayjp'])->name('post.payjp');
    // Route::get('/paypal-success', [\App\Http\Controllers\PaymentController::class, 'paypalSuccess'])->name('paypal.success');
    // Route::get('/paypal-cancel', [\App\Http\Controllers\PaymentController::class, 'paypalCancel'])->name('paypal.cancel');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
