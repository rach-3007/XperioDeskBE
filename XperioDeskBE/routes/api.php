<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\MicrosoftAuthController;
use App\Http\Controllers\ConcurrentBookingController;



Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);



Route::middleware('auth:api')->group(function () {
    Route::prefix('seats')->group(function () {
        Route::get('/', [SeatController::class, 'index']);
        Route::get('{id}', [SeatController::class, 'show']);
        Route::post('/', [SeatController::class, 'store']);
        Route::put('{id}', [SeatController::class, 'update']);
        Route::put('{id}/soft-delete', [SeatController::class, 'destroy']); //Soft-Deletion
        Route::get('{id}/availability', [SeatController::class, 'checkAvailability']);
        Route::put('seats-restore/{id}', [SeatController::class, 'restore']);
    
});
    
    //User Routes
        Route::get('/users/{id}', [UserController::class, 'getUserById']);
        Route::put('user/change-password', [UserController::class, 'changePassword']);
        Route::get('/users-with-roles', [UserController::class, 'getUsersWithRoles']);
        Route::put('/users/{id}/update-role', [UserController::class, 'updateUserRole']);
        


        // Route for locking a seat
        Route::post('/seats/lock', [ConcurrentBookingController::class, 'lockSeat']);
        // Route for releasing a seat
        Route::post('/seats/release', [ConcurrentBookingController::class, 'releaseSeat']);
    
    

    // Layout Routes
    Route::get('/layouts', [LayoutController::class, 'index']);
    Route::get('/layouts/{id}', [LayoutController::class, 'show']);
    // Route::post('/layouts', [LayoutController::class, 'store']);
    Route::put('/layouts/{id}', [LayoutController::class, 'update']);
    Route::delete('/layouts/{id}', [LayoutController::class, 'destroy']);
    Route::post('/layouts', [LayoutController::class, 'createLayoutWithEntities']);
    Route::get('layouts/{id}/entities', [LayoutController::class, 'getLayoutWithEntities']);

    // Module Routes
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::get('/modules/{id}', [ModuleController::class, 'show']);
    Route::post('/modules', [ModuleController::class, 'store']);
    Route::put('/modules/{id}', [ModuleController::class, 'update']);
    Route::delete('/modules/{id}', [ModuleController::class, 'destroy']);



    Route::get('/admin/users', [AdminController::class, 'viewAllUsers']);
   

  

    // Bulk Cancel Bookings
    Route::delete('/admin/cancel-bookings', [AdminController::class, 'bulkCancelBookings']);

    // Bulk Assign Seats to Users
    Route::post('/admin/assign-seats', [AdminController::class, 'bulkAssignSeats']);

    // Search and Filter Users
    Route::get('/admin/search-users', [AdminController::class, 'searchUsers']);

    // Search and Filter Bookings
    Route::get('/admin/search-bookings', [AdminController::class, 'searchBookings']);



   



    Route::get('/admin/search-bookings', [AdminController::class, 'searchBookings']);   
    //User USER USER Booking3   
    Route::post('/user/book-seat', [UserBookingController::class, 'bookSeat']);;
    Route::post('/admin/assign-seat', [AdminController::class, 'assignSeat']);
    Route::post('/assign-permanent-seat', [AdminController::class, 'assignPermanentSeat']);

    
    Route::get('/admin/users', [AdminController::class, 'viewAllUsers']);
    // Assign Seat to User
    Route::post('/user/cancel-booking', [UserBookingController::class, 'cancelBooking']);
    Route::get('/bookings/user', [UserBookingController::class, 'getUserBookings']);
   
});
// MICROSOFT LOGIN
Route::post('/loginAzure', [MicrosoftAuthController::class,'loginAzure']);


// Route::get('/analytics/total-seats-vs-bookings', [AnalyticsController::class, 'getTotalSeatsVsBookings']);
// Route::get('/analytics/seat-occupancy', [AnalyticsController::class, 'getSeatOccupancy']);
// Route::get('/analytics/du-wise-bookings', [AnalyticsController::class, 'getDuWiseBookings']);
// Route::get('/analytics/today-bookings', [AnalyticsController::class, 'getTodayBookings']);
// Route::get('/analytics/today-utilization-rate', [AnalyticsController::class, 'getTodayUtilizationRate']);
