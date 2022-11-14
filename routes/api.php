<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('', [RouteController::class, 'index'])->name('api.index');

Route::prefix('product')->as('product.')->group(function () {
    Route::get('', [ProductController::class, 'index'])->name('index');
    Route::post('', [ProductController::class, 'store'])->name('store');
    Route::get('{product}', [ProductController::class, 'show'])->name('show');
    Route::put('{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('{product}', [ProductController::class, 'delete'])->name('delete');
});
