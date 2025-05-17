<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('settings')->middleware('auth:api')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});
