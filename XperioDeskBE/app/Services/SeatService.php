<?php

namespace App\Services;

use App\Models\Seat;
use App\ServiceInterfaces\SeatServiceInterface;
use Illuminate\Http\Request;

class SeatService implements SeatServiceInterface
{
    public function listAllSeats()
    {
        return Seat::all();
    }

    public function getSeatDetails($id)
    {
        return Seat::findOrFail($id);
    }

    public function createSeat(Request $request)
    {
        $data = $request->all();
        return Seat::create($data);
    }

    public function updateSeat($id, Request $request)
    {
        $seat = Seat::findOrFail($id);
        $data = $request->all();
        $seat->update($data);
        return $seat;
    }

    public function deleteSeat($id)
    {
        $seat = Seat::findOrFail($id);
        $seat->delete();  // This will perform a soft delete
        return $seat;
    }

    public function checkSeatAvailability($id)
    {
        $seat = Seat::findOrFail($id);
        return $seat->status === 'available';
    }

    public function restoreSeat($id)
    {
        $seat = Seat::withTrashed()->findOrFail($id);
        $seat->restore();
        return $seat;
    }
}
