<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CollectionController;
use Illuminate\Support\Facades\Route;

// Endpoint publik untuk autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Seluruh endpoint di bawah ini wajib membawa Bearer Token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD Tabel 1 (Kategori) & Tabel 2 (Item Koleksi)
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/collections', CollectionController::class);
});