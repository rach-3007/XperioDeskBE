<?php

namespace App\Http\Controllers;

use App\Services\ConcurrentBookingServiceInterface;
use Illuminate\Http\Request;

class ConcurrentBookingController extends Controller
{
    protected $seatBookingService;

    public function __construct(ConcurrentBookingServiceInterface $seatBookingService)
    {
        $this->seatBookingService = $seatBookingService;
    }

    public function lockSeat(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|integer|exists:seats,id',
        ]);

        $userId = auth()->id();
        $seatId = $request->input('seat_id');

        $isLocked = $this->seatBookingService->lockSeat($seatId, $userId);

        if ($isLocked) {
            return response()->json(['message' => 'Seat locked successfully.'], 200);
        } else {
            return response()->json(['message' => 'Seat is not available or already locked.'], 409);
        }
    }

    public function bookSeat(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|integer|exists:seats,id',
        ]);

        $userId = auth()->id();
        $seatId = $request->input('seat_id');

        $isBooked = $this->seatBookingService->bookSeat($seatId, $userId);

        if ($isBooked) {
            return response()->json(['message' => 'Seat booked successfully.'], 200);
        } else {
            return response()->json(['message' => 'Seat could not be booked.'], 409);
        }
    }

    public function releaseSeat(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|integer|exists:seats,id',
        ]);

        $userId = auth()->id();
        $seatId = $request->input('seat_id');

        $this->seatBookingService->releaseSeat($seatId, $userId);

        return response()->json(['message' => 'Seat released successfully.'], 200);
    }
}
