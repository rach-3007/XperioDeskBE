<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\ModuleController;


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AnalyticsController;

    Route::prefix('seats')->group(function () {
        Route::get('/', [SeatController::class, 'index']);
        Route::get('{id}', [SeatController::class, 'show']);
        Route::post('/', [SeatController::class, 'store']);
        Route::put('{id}', [SeatController::class, 'update']);
        Route::put('{id}/soft-delete', [SeatController::class, 'destroy']); //Soft-Deletion
        Route::get('{id}/availability', [SeatController::class, 'checkAvailability']);
        Route::put('seats-restore/{id}', [SeatController::class, 'restore']);
    
});
    
    Route::prefix('user')->group(function () {
        Route::put('change-password', [UserController::class, 'changePassword']);
        Route::put('role/{id}', [UserController::class, 'updateRole']);
    });
    

    // Layout Routes
    Route::get('/layouts', [LayoutController::class, 'index']);
    Route::get('/layouts/{id}', [LayoutController::class, 'show']);
    Route::post('/layouts', [LayoutController::class, 'store']);
    Route::put('/layouts/{id}', [LayoutController::class, 'update']);
    Route::delete('/layouts/{id}', [LayoutController::class, 'destroy']);
    Route::post('/layouts', [LayoutController::class, 'createLayout']);

    // Module Routes
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::get('/modules/{id}', [ModuleController::class, 'show']);
    Route::post('/modules', [ModuleController::class, 'store']);
    Route::put('/modules/{id}', [ModuleController::class, 'update']);
    Route::delete('/modules/{id}', [ModuleController::class, 'destroy']);

});


Route::get('/admin/users', [AdminController::class, 'viewAllUsers']);
// View All Bookings for a Specific User
Route::get('/admin/users/{id}/bookings', [AdminController::class, 'viewUserBookings']);

// Cancel a Booking (Soft Delete)
Route::delete('/bookings/{id}', [AdminController::class, 'cancelBooking']);

// Assign Seat to User
Route::post('/admin/assign-seat', [AdminController::class, 'assignSeat']);

// Bulk Cancel Bookings
Route::delete('/admin/cancel-bookings', [AdminController::class, 'bulkCancelBookings']);

// Bulk Assign Seats to Users
Route::post('/admin/assign-seats', [AdminController::class, 'bulkAssignSeats']);

// Search and Filter Users
Route::get('/admin/search-users', [AdminController::class, 'searchUsers']);

// Search and Filter Bookings
Route::get('/admin/search-bookings', [AdminController::class, 'searchBookings']);

// Route::get('/analytics/total-seats-vs-bookings', [AnalyticsController::class, 'getTotalSeatsVsBookings']);
// Route::get('/analytics/seat-occupancy', [AnalyticsController::class, 'getSeatOccupancy']);
// Route::get('/analytics/du-wise-bookings', [AnalyticsController::class, 'getDuWiseBookings']);
// Route::get('/analytics/today-bookings', [AnalyticsController::class, 'getTodayBookings']);
// Route::get('/analytics/today-utilization-rate', [AnalyticsController::class, 'getTodayUtilizationRate']);
