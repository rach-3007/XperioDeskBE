<?php
namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface UserBookingServiceInterface
{
    public function bookSeat(Request $request);
    // public function getUserBookings();
}