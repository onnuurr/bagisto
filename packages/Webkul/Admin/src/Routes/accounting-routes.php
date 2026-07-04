<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Accounting\AccountController;
use Webkul\Admin\Http\Controllers\Accounting\FiscalYearController;
use Webkul\Admin\Http\Controllers\Accounting\JournalEntryController;
use Webkul\Admin\Http\Controllers\Accounting\LedgerController;
use Webkul\Admin\Http\Controllers\Accounting\Reports\BalanceSheetController;
use Webkul\Admin\Http\Controllers\Accounting\Reports\IncomeStatementController;
use Webkul\Admin\Http\Controllers\Accounting\Reports\TrialBalanceController;
use Webkul\Admin\Http\Controllers\Accounting\SettingController;

/**
 * Accounting routes.
 */
Route::prefix('accounting')->group(function () {
    Route::controller(AccountController::class)->prefix('accounts')->group(function () {
        Route::get('/', 'index')->name('admin.accounting.accounts.index');

        Route::get('create', 'create')->name('admin.accounting.accounts.create');

        Route::post('create', 'store')->name('admin.accounting.accounts.store');

        Route::get('edit/{id}', 'edit')->name('admin.accounting.accounts.edit');

        Route::put('edit/{id}', 'update')->name('admin.accounting.accounts.update');

        Route::delete('{id}', 'delete')->name('admin.accounting.accounts.delete');

        Route::post('mass-delete', 'massDelete')->name('admin.accounting.accounts.mass_delete');
    });

    Route::controller(JournalEntryController::class)->prefix('journal-entries')->group(function () {
        Route::get('/', 'index')->name('admin.accounting.journal_entries.index');

        Route::get('create', 'create')->name('admin.accounting.journal_entries.create');

        Route::post('create', 'store')->name('admin.accounting.journal_entries.store');

        Route::get('{id}', 'view')->name('admin.accounting.journal_entries.view');

        Route::post('{id}/post', 'post')->name('admin.accounting.journal_entries.post');

        Route::post('{id}/void', 'void')->name('admin.accounting.journal_entries.void');
    });

    Route::controller(LedgerController::class)->prefix('ledger')->group(function () {
        Route::get('/', 'index')->name('admin.accounting.ledger.index');
    });

    Route::prefix('reports')->group(function () {
        Route::get('trial-balance', [TrialBalanceController::class, 'index'])->name('admin.accounting.reports.trial_balance');

        Route::get('income-statement', [IncomeStatementController::class, 'index'])->name('admin.accounting.reports.income_statement');

        Route::get('balance-sheet', [BalanceSheetController::class, 'index'])->name('admin.accounting.reports.balance_sheet');
    });

    Route::controller(FiscalYearController::class)->prefix('fiscal-years')->group(function () {
        Route::get('/', 'index')->name('admin.accounting.fiscal_years.index');

        Route::get('create', 'create')->name('admin.accounting.fiscal_years.create');

        Route::post('create', 'store')->name('admin.accounting.fiscal_years.store');

        Route::post('{id}/close', 'close')->name('admin.accounting.fiscal_years.close');
    });

    Route::controller(SettingController::class)->prefix('settings')->group(function () {
        Route::get('/', 'edit')->name('admin.accounting.settings.edit');

        Route::post('/', 'update')->name('admin.accounting.settings.update');
    });
});
