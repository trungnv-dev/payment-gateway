<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GMOPayment\HomeController;
use App\Http\Controllers\GMOPayment\MemberController;
use App\Http\Controllers\GMOPayment\CardController;
use App\Http\Controllers\GMOPayment\OrderController;

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

    Route::group(['prefix' => 'gmo'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('payment.gmo.index');
        // member
        Route::controller(MemberController::class)->prefix('member')->name('payment.gmo.member.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}', 'show')->name('show');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });
        // card
        Route::controller(CardController::class)->prefix('card')->name('payment.gmo.card.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::delete('/', 'destroy')->name('destroy');
        });
        // charge
        Route::controller(OrderController::class)->prefix('order')->name('payment.gmo.order.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::get('/{order}', 'show')->name('show');
            Route::post('/exec-tran/{order}', 'execTran')->name('execTran');
        });
    });
});

// Export
Route::get('/export-user', [\App\Http\Controllers\ExportController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
