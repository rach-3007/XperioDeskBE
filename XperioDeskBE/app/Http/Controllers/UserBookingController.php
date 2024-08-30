<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\ServiceInterfaces\UserBookingServiceInterface;
class UserBookingController extends Controller
{
    protected $userService;

    public function __construct(UserBookingServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function bookSeat(Request $request)
    {
        try {
            $booking = $this->userService->bookSeat($request);

            return response()->json([
                'success' => true,
                'message' => 'Seat booked successfully',
                'data' => $booking,
            ]);
        } catch (\InvalidArgumentException $e) { 
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400); 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error booking seat: ' . $e->getMessage(),
            ], 500); 
        }
    }

    // public function viewMyBookings()
    // {
    //     try {
    //         $bookings = $this->userService->getUserBookings();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Your bookings retrieved successfully',
    //             'data' => $bookings,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error retrieving your bookings: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
}