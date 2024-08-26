<?php

namespace App\Services;

interface LayoutServiceInterface
{
    public function createLayout(array $data);
    public function updateLayout(int $layoutId, array $data);
    public function getLayout(int $layoutId);
    public function deleteLayout(int $layoutId);
    public function addEntityToLayout(int $layoutId, array $data);
    public function updateEntity(int $entityId, array $data);
    public function deleteEntity(int $entityId);
    // public function getSeat(int $seatId);
    // public function bookSeat(int $seatId, int $userId);
    // public function cancelBooking(int $seatId);
}
