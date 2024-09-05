<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\ServiceInterfaces\UserBookingServiceInterface;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;
class UserBookingController extends Controller
{
    protected $userService;

    // public function __construct(UserBookingServiceInterface $userService)
    // {
    //     $this->userService = $userService;
    // }

    // public function bookSeat(Request $request)
    // {
    //     try {
    //         $booking = $this->userService->bookSeat($request);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Seat booked successfully',
    //             'data' => $booking,
    //         ]);
    //     } catch (\InvalidArgumentException $e) { 
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], 400); 
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error booking seat: ' . $e->getMessage(),
    //         ], 500); 
    //     }
    // }

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

    public function cancelBooking(Request $request)
    {
        try {
            $response = $this->userService->cancelBooking($request);
    
            return $response; 
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    //get user booing
    public function getUserBookings(Request $request)
{
    try {
        $bookings = $this->userService->getUserBookings($request);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching bookings: ' . $e->getMessage(),
        ], 500);
    }
}

    
public function __construct(UserBookingServiceInterface $userService)
{
    $this->userService = $userService;
}

public function bookSeat(Request $request)
{
    try {
        $booking = $this->userService->bookSeat($request);

        // Send booking confirmation email
        $details = [
            'name' => $request->input('name'),
            'booking_date' => $booking['booking_date'], // Adjust according to your data
            // Add other necessary details
        ];

        Mail::to($request->input('email'))->send(new BookingConfirmationMail($details));

        return response()->json([
            'success' => true,
            'message' => 'Seat booked successfully and confirmation email sent.',
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

}