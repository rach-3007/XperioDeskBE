<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LayoutController;

// Route::prefix('seats')->group(function () {
//     Route::get('/', [SeatController::class, 'index']);
//     Route::get('{id}', [SeatController::class, 'show']);
//     Route::post('/', [SeatController::class, 'store']);
//     Route::put('{id}', [SeatController::class, 'update']);
//     Route::put('{id}/soft-delete', [SeatController::class, 'destroy']); //Soft-Deletion
//     Route::get('{id}/availability', [SeatController::class, 'checkAvailability']);
//     Route::put('seats-restore/{id}', [SeatController::class, 'restore']);
// });

// Route::prefix('layouts')->group(function () {
//     Route::post('/', [LayoutController::class, 'store']);
//     Route::put('{id}', [LayoutController::class, 'update']);
//     Route::get('{id}', [LayoutController::class, 'show']);
//     Route::delete('{id}', [LayoutController::class, 'destroy']);

// });
Route::get('{id}/entities', [LayoutController::class, 'getLayoutWithEntities']);


Route::post('/layouts/save', [LayoutController::class, 'save']);

Route::prefix('user')->group(function () {
    Route::put('change-password', [UserController::class, 'changePassword']);
    Route::put('role/{id}', [UserController::class, 'updateRole']);
});
