<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\ServiceInterfaces\UserBookingServiceInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class UserBookingService implements UserBookingServiceInterface
{public function bookSeat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seat_id' => 'required|exists:seats,id',
            'start_date' => 'required|date|before_or_equal:end_date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|after_or_equal:today',
        ]);
    
        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation error: ' . $validator->errors()->first());
        }
    
        // Check if the booking duration exceeds 2 days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
    
        if ($startDate->diffInDays($endDate) > 2) {
            throw new \InvalidArgumentException('You can only book a seat for a maximum of 2 days.');
        }
    
        DB::beginTransaction();
    
        try {
            // Check if the user already has any active booking
            $existingUserBooking = Booking::where('user_id', Auth::user()->id)
                ->where('end_date', '>=', now())->exists();
    
            if ($existingUserBooking) {
                throw new \InvalidArgumentException('You already have an active booking and cannot book another seat.');
            }
    
            // Lock the seat row for update
            $seat = Seat::where('id', $request->seat_id)->lockForUpdate()->first();
    
            if ($seat->is_disabled) {
                throw new \InvalidArgumentException('Seat is unavailable.');
            }
    
            // Check if the seat is already booked for the selected date range
            $existingSeatBooking = Booking::where('seat_id', $seat->id)
                ->where(function ($query) use ($request) {
                    $query->where('start_date', '<=', $request->end_date)
                        ->where('end_date', '>=', $request->start_date);
                })->first();
    
            if ($existingSeatBooking) {
                throw new \InvalidArgumentException('Seat is already booked for the selected date range.');
            }
    
            // Create booking
            $booking = Booking::create([
                'seat_id' => $seat->id,
                'user_id' => Auth::user()->id,
                'booked_by' => Auth::user()->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
    
            // Update seat status
            $seat->update([
                'status' => 'booked',
                'booked_by_user_id' => Auth::user()->id,
            ]);
    
            DB::commit();
    
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function cancelBooking(Request $request)
{
    $validator = Validator::make($request->all(), [
        'booking_id' => 'required|exists:bookings,id',
    ]);

    if ($validator->fails()) {
        throw new \InvalidArgumentException('Validation error: ' . $validator->errors()->first());
    }

    DB::beginTransaction();

    try {
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::user()->id) // Ensure the user owns the booking
            ->first();

        if (!$booking) {
            throw new \InvalidArgumentException('Booking not found or you do not have permission to cancel this booking.');
        }

        // Update the seat status
        $seat = Seat::where('id', $booking->seat_id)->lockForUpdate()->first();
        $seat->update([
            'status' => 'available',
            'booked_by_user_id' => null,
        ]);

        // Delete the booking
        $booking->delete();

        DB::commit();

        return response()->json(['message' => 'Booking canceled successfully.'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}


// get user bookings
public function getUserBookings(Request $request)
{
    $userId = Auth::user()->id;

    // Fetch the user's bookings from the database
    $bookings = Booking::where('user_id', $userId)
                ->orderBy('start_date', 'desc')
                ->get();

    return $bookings;
}





}




