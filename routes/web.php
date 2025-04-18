<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\ShortUrlController;

Route::post('/shorten', [ShortUrlController::class, 'store']);

Route::get('/shorten/{code}', [ShortUrlController::class, 'show']);

Route::put('/shorten/{code}', [ShortUrlController::class, 'update']);

Route::delete('/shorten/{code}', [ShortUrlController::class, 'destroy']);

Route::get('/shorten/{code}/stats', [ShortUrlController::class, 'stats']);