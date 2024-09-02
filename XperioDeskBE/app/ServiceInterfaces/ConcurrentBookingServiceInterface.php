<?php

namespace App\Services;

interface ConcurrentBookingServiceInterface
{
    public function lockSeat(int $seatId, int $userId): bool;
    public function bookSeat(int $seatId, int $userId): bool;
    public function releaseSeat(int $seatId, int $userId): void;
}
