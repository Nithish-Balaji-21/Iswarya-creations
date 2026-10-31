<?php

use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::get('/search', [ProductApiController::class, 'search'])->name('api.search');
    Route::get('/products/{product:slug}', [ProductApiController::class, 'show'])->name('api.products.show');
});
