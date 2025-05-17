<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    //
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
