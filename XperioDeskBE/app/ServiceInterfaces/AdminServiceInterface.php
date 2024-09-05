<?php
namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface AdminServiceInterface
{
    public function getAllUsers();
    public function getUserBookings($userId);
    public function cancelBooking($bookingId);
    public function assignSeat(Request $request);
    public function bulkCancelBookings(Request $request);
    public function bulkAssignSeats(Request $request);
    public function searchUsers(Request $request);
    public function searchBookings(Request $request);
    public function assignPermanentSeat(Request $request);
    public function getBookingCount();

}