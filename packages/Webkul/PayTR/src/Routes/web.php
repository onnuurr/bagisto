<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Webkul\PayTR\Http\Controllers\PayTRController;

Route::group(['middleware' => ['web']], function () {
    Route::controller(PayTRController::class)
        ->prefix('paytr')
        ->group(function () {
            Route::get('redirect', 'redirect')->name('paytr.redirect');

            Route::post('notify', 'notify')
                ->withoutMiddleware(VerifyCsrfToken::class)
                ->name('paytr.notify');

            Route::get('ok', 'ok')->name('paytr.ok');

            Route::get('fail', 'fail')->name('paytr.fail');
        });
});
