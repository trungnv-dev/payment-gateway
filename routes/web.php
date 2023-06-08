<?php

use App\Http\Controllers\FileStreamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GMOPayment\HomeController;
use App\Http\Controllers\GMOPayment\MemberController;
use App\Http\Controllers\GMOPayment\CardController;
use App\Http\Controllers\GMOPayment\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;

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

Route::get('paypay', function () {
    return view('paypay-test');
});

Route::post('paypay/result', function (Request $request) {
    return response()->json($request->all());
});

// Route::post('paypay/result', [HomeController::class, 'payPay']);

Route::group(['prefix' => 'payment', 'middleware' => 'auth'], function () {
    Route::get('/paypal', [PaymentController::class, 'paypal']);
    Route::get('/payjp', [PaymentController::class, 'payjp']);
    Route::post('/payjp', [PaymentController::class, 'paymentPayjp'])->name('post.payjp');
    // Route::get('/paypal-success', [\App\Http\Controllers\PaymentController::class, 'paypalSuccess'])->name('paypal.success');
    // Route::get('/paypal-cancel', [\App\Http\Controllers\PaymentController::class, 'paypalCancel'])->name('paypal.cancel');

    Route::group(['prefix' => 'gmo'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('payment.gmo.index');
        Route::get('/credit-cards', [HomeController::class, 'creditCard'])->name('payment.gmo.credit_card');
        Route::get('/paypay', [HomeController::class, 'payPay'])->name('payment.gmo.paypay');
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
            Route::post('/{order}/secure/{type?}', 'secureTran')->name('secureTran');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::get('/{order}', 'show')->name('show');
            Route::post('/exec-tran/{order}', 'execTran')->name('execTran');
            Route::post('/alter-tran/{order}', 'alterTran')->name('alterTran');
        });
    });
});

// File stream
Route::group(['prefix' => 'file-stream', 'middleware' => 'auth'], function () {
    Route::get('/', [FileStreamController::class, 'index'])->name('file_stream.index');
    Route::get('/export', [FileStreamController::class, 'export'])->name('file_stream.export');
    Route::get('/download', [FileStreamController::class, 'download'])->name('file_stream.download');
    Route::get('/copy', [FileStreamController::class, 'copy'])->name('file_stream.copy');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
