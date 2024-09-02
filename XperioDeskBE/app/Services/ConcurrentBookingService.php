<?php

namespace App\Services;

use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Exception;

class ConcurrentBookingService implements ConcurrentBookingServiceInterface
{
    public function lockSeat(int $seatId, int $userId): bool
    {
        try {
            DB::beginTransaction();

            $seat = Seat::where('id', $seatId)->lockForUpdate()->first();

            if (!$seat || $seat->status !== 'available' || $seat->is_active == false) {
                DB::rollBack();
                return false;
            }

            $seat->status = 'locked';
            $seat->booked_by_user_id = $userId;
            $seat->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function bookSeat(int $seatId, int $userId): bool
    {
        try {
            DB::beginTransaction();

            $seat = Seat::where('id', $seatId)->lockForUpdate()->first();

            if (!$seat || $seat->status !== 'locked' || $seat->booked_by_user_id !== $userId) {
                DB::rollBack();
                return false;
            }

            $seat->status = 'booked';
            $seat->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function releaseSeat(int $seatId, int $userId): void
    {
        DB::transaction(function () use ($seatId, $userId) {
            $seat = Seat::where('id', $seatId)->lockForUpdate()->first();

            if ($seat && $seat->booked_by_user_id === $userId && $seat->status === 'locked') {
                $seat->status = 'available';
                $seat->booked_by_user_id = null;
                $seat->save();
            }
        });
    }
}
