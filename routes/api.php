<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FoodController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/food')->group(function () {
    Route::get('/', [FoodController::class, 'index']);
    Route::post('/', [FoodController::class, 'store']);
    Route::put('/{id}', [FoodController::class, 'update'])->where('id', '[0-9]+');
    Route::get('/{food}', [FoodController::class, 'show'])->where('food', '[0-9]+');
    Route::delete('/{id}', [FoodController::class, 'destroy'])->where('id', '[0-9]+');
});

Route::prefix('/category/food')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{category}', [CategoryController::class, 'update']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);
});
