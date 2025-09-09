<?php

use Illuminate\Support\Facades\Route;

// other web routes, sanctum etc.
Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);

// finally, SPA fallback
Route::view('/{any}', 'app')->where('any', '.*');
