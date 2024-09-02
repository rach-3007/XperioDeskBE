<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\ServiceInterfaces\AnalyticsServiceInterface;

class AnalyticsService implements AnalyticsServiceInterface
{
    public function getTotalSeatsVsBookings(): JsonResponse
    {
        $data = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as total_bookings')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $totalSeats = Seat::count();

        return response()->json([
            'labels' => $data->pluck('month'),
            'datasets' => [
                'totalSeats' => $totalSeats,
                'totalBookings' => $data->pluck('total_bookings')
            ]
        ]);
    }

    public function getSeatOccupancy(): JsonResponse
    {
        $totalSeats = Seat::count();
        $bookedSeats = Booking::whereNull('cancellation_date')->count();
        $permanentlyAllocatedSeats = Booking::whereNull('end_date')->count();
        $vacantSeats = $totalSeats - ($bookedSeats + $permanentlyAllocatedSeats);

        return response()->json([
            'permanentlyAllocatedSeats' => $permanentlyAllocatedSeats,
            'vacantSeats' => $vacantSeats,
            'bookedSeats' => $bookedSeats
        ]);
    }

    public function getDuWiseBookings(): JsonResponse
    {
        $data = Booking::selectRaw('du_id, COUNT(*) as total_bookings')
            ->groupBy('du_id')
            ->get();

        return response()->json([
            'labels' => $data->pluck('du_id'),
            'datasets' => $data->pluck('total_bookings')
        ]);
    }

    public function getTodayBookings(): JsonResponse
    {
        $totalSeats = Seat::count();
        $todayBookings = Booking::whereDate('created_at', Carbon::today())->count();

        return response()->json([
            'totalSeats' => $totalSeats,
            'todayBookings' => $todayBookings
        ]);
    }

    public function getTodayUtilizationRate(): JsonResponse
    {
        $totalSeats = Seat::count();
        $todayBookings = Booking::whereDate('created_at', Carbon::today())->count();

        $utilizationRate = ($totalSeats > 0) ? ($todayBookings / $totalSeats) * 100 : 0;

        return response()->json([
            'utilizationRate' => $utilizationRate
        ]);
    }
}