<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{product}/edit', [ProductController::class, 'edit']);

Route::post('/products', [ProductController::class, 'store']);

Route::put('/products/{products}', [ProductController::class, 'update']);

Route::delete('/products/{products}', [ProductController::class, 'destroy']);