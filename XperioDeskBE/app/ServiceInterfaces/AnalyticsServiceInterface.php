<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\JsonResponse;

interface AnalyticsServiceInterface
{
    public function getTotalSeatsVsBookings(): JsonResponse;
    public function getSeatOccupancy(): JsonResponse;
    public function getDuWiseBookings(): JsonResponse;
    public function getTodayBookings(): JsonResponse;
    public function getTodayUtilizationRate(): JsonResponse;
}