<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\ServiceInterfaces\AdminServiceInterface;

class AdminService implements AdminServiceInterface
{
    public function getAllUsers()
    {
        return User::get();
    }

    public function getUserBookings($userId)
    {
        return User::with('bookings.seat')->findOrFail($userId)->bookings;
    }

    public function cancelBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'canceled']);

        if ($booking->start_date < now()) {
            $booking->seat->update(['status' => 'available']);
        }

        return $booking;
    }
    //Assign seats to a single user
    public function assignSeat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seat_id' => 'required|exists:seats,id',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation error: ' . $validator->errors());
        }

        $seat = Seat::findOrFail($request->seat_id);
        $existingBooking = Booking::where('seat_id', $seat->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->first();

        if ($existingBooking) {
            throw new \InvalidArgumentException('Seat is already booked for the selected date range');
        }

        $booking = Booking::create([
            'seat_id' => $seat->id,
            'user_id' => $request->user_id,
            'booked_by' => Auth::user()->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $seat->update(['status' => 'booked']);

        return $booking;
    }

    public function bulkCancelBookings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation error: ' . $validator->errors());
        }

        $bookings = Booking::whereIn('id', $request->booking_ids)->get();

        foreach ($bookings as $booking) {
            $booking->update(['status' => 'canceled']);

            if ($booking->start_date < now()) {
                $booking->seat->update(['status' => 'available']);
            }
        }

        return $bookings;
    }

    public function bulkAssignSeats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assignments' => 'required|array',
            'assignments.*.seat_id' => 'required|exists:seats,id',
            'assignments.*.user_id' => 'required|exists:users,id',
            'assignments.*.booked_by' => 'required|exists:users,id',
            'assignments.*.booked_for' => 'required|date',
            'assignments.*.start_date' => 'required|date|before_or_equal:assignments.*.end_date',
            'assignments.*.end_date' => 'required|date|after_or_equal:assignments.*.start_date',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation error: ' . $validator->errors());
        }

        $seatIds = array_column($request->assignments, 'seat_id');
        $dateRanges = [];
        foreach ($request->assignments as $assignment) {
            $dateRanges[] = [$assignment['start_date'], $assignment['end_date']];
        }

        // Check for conflicts and seat active status
        $hasConflictOrInactiveSeat = Booking::whereIn('seat_id', $seatIds)
            ->where(function ($query) use ($dateRanges) {
                foreach ($dateRanges as $range) {
                    $query->orWhereBetween('start_date', $range)
                        ->orWhereBetween('end_date', $range)
                        ->orWhere(function ($q) use ($range) {
                            $q->where('start_date', '<=', $range[0])
                                ->where('end_date', '>=', $range[1]);
                        });
                }
            })
            ->orWhereHas('seat', function ($query) {
                $query->where('is_active', false);
            })
            ->exists();

        if ($hasConflictOrInactiveSeat) {
            throw new \InvalidArgumentException('One or more seats are already booked for the specified date range or are inactive.');
        }

        $assignments = [];
        DB::transaction(function () use ($request, &$assignments) {
            foreach ($request->assignments as $assignment) {
                $seat = Seat::findOrFail($assignment['seat_id']);

                $booking = Booking::create([
                    'seat_id' => $seat->id,
                    'user_id' => $assignment['user_id'],
                    'booked_by' => $assignment['booked_by'],
                    'start_date' => $assignment['start_date'],
                    'end_date' => $assignment['end_date'],
                ]);

                $seat->update(['status' => 'booked']);
                $assignments[] = $booking;
            }
        });

        return $assignments;
    }

    public function searchUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('du_id')) {
            $query->where('du_id', $request->du_id);
        }

        return $query->get();
    }

    public function searchBookings(Request $request)
    {
        $query = Booking::with('seat', 'user');

        if ($request->filled('seat_number')) {
            $query->whereHas('seat', function ($q) use ($request) {
                $q->where('seat_number', 'like', '%' . $request->seat_number . '%');
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('du_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('du_id', $request->du_id);
            });
        }

        return $query->get();
    }
}