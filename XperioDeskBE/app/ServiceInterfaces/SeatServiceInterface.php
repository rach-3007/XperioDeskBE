<?php

namespace App\ServiceInterfaces;

use App\Models\Seat;

interface SeatServiceInterface
{
    public function listAllSeats();
    public function getSeatDetails($id);
    public function createSeat(array $data);
    public function updateSeat($id, array $data);
    public function deleteSeat($id);
    public function checkSeatAvailability($id);
    public function restoreSeat($id);
}
