<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Webkul\Iyzico\Http\Controllers\IyzicoController;

Route::group(['middleware' => ['web']], function () {
    Route::controller(IyzicoController::class)
        ->prefix('iyzico')
        ->group(function () {
            Route::get('redirect', 'redirect')->name('iyzico.redirect');

            Route::post('callback', 'callback')
                ->withoutMiddleware(VerifyCsrfToken::class)
                ->name('iyzico.callback');
        });
});
