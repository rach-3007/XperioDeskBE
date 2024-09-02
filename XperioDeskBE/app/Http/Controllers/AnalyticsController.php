<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    // Total seats vs. total bookings per month
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

    // Seat occupancy: Permanently allocated, vacant, and booked seats
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


    // DU-wise booking statistics
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

    // Total seats vs. total bookings today
    public function getTodayBookings(): JsonResponse
    {
        $totalSeats = Seat::count();
        $todayBookings = Booking::whereDate('created_at', Carbon::today())->count();

        return response()->json([
            'totalSeats' => $totalSeats,
            'todayBookings' => $todayBookings
        ]);
    }

    // Today's utilization rate
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
