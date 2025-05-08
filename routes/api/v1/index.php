<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store'])->withoutMiddleware([
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, // Hago esto para evitar el error 419 por csrf token
]);
Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('can:show,order');
