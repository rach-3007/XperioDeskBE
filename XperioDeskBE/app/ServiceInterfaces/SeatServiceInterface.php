<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface SeatServiceInterface
{
    public function listAllSeats();
    public function getSeatDetails($id);
    public function createSeat(Request $request);
    public function updateSeat($id, Request $request);
    public function deleteSeat($id);
    public function checkSeatAvailability($id);
    public function restoreSeat($id);
}
