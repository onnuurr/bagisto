<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\SMS\SmsLogController;

/**
 * SMS routes.
 */
Route::controller(SmsLogController::class)->prefix('sms')->group(function () {
    Route::get('/', 'index')->name('admin.sms.index');

    Route::delete('{id}', 'delete')->name('admin.sms.delete');

    Route::post('mass-delete', 'massDelete')->name('admin.sms.mass_delete');
});
