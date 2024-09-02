<?php

namespace App\Http\Controllers;

use App\ServiceInterfaces\AnalyticsServiceInterface;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsServiceInterface $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getTotalSeatsVsBookings(): JsonResponse
    {
        return $this->analyticsService->getTotalSeatsVsBookings();
    }

    public function getSeatOccupancy(): JsonResponse
    {
        return $this->analyticsService->getSeatOccupancy();
    }

    public function getDuWiseBookings(): JsonResponse
    {
        return $this->analyticsService->getDuWiseBookings();
    }

    public function getTodayBookings(): JsonResponse
    {
        return $this->analyticsService->getTodayBookings();
    }

    public function getTodayUtilizationRate(): JsonResponse
    {
        return $this->analyticsService->getTodayUtilizationRate();
    }
}