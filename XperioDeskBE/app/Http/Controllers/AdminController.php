<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\ServiceInterfaces\AdminServiceInterface;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    // 1. View All Users
    public function viewAllUsers()
    {
        try {
            $users = $this->adminService->getAllUsers();

            return response()->json([
                'success' => true,
                'message' => 'All users retrieved successfully',
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving users: ' . $e->getMessage(),
            ], 500); 
        }
    }

    // 2. View All Bookings for a Specific User
    public function viewUserBookings($id)
    {
        try {
            $bookings = $this->adminService->getUserBookings($id);

            return response()->json([
                'success' => true,
                'message' => 'User bookings retrieved successfully',
                'data' => $bookings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving user bookings: ' . $e->getMessage(),
            ], 500); 
        }
    }

    // 3. Cancel a Booking
    public function cancelBooking($id)
    {
        try {
            $booking = $this->adminService->cancelBooking($id);

            return response()->json([
                'success' => true,
                'message' => 'Booking canceled successfully',
                'data' => $booking,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error canceling booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 4. Assign Seat to User
    public function assignSeat(Request $request)
    {
        try {
            $booking = $this->adminService->assignSeat($request);

            return response()->json([
                'success' => true,
                'message' => 'Seat assigned to user successfully',
                'data' => $booking,
            ]);
        } catch (\InvalidArgumentException $e) { // Catch validation or conflict errors
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400); // Bad Request for validation errors, 409 Conflict for booking conflicts
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning seat: ' . $e->getMessage(),
            ], 500); 
        }
    }



    // 5. Bulk Cancel Bookings
    public function bulkCancelBookings(Request $request)
    {
        try {
            $bookings = $this->adminService->bulkCancelBookings($request);

            return response()->json([
                'success' => true,
                'message' => 'Bookings canceled successfully',
                'data' => $bookings,
            ]);
        } catch (\InvalidArgumentException $e) { 
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400); 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error canceling bookings: ' . $e->getMessage(),
            ], 500); 
        }
    }



    // 6. Bulk Assign Seats to Users
    public function bulkAssignSeats(Request $request)
    {
        try {
            $assignments = $this->adminService->bulkAssignSeats($request);

            return response()->json([
                'success' => true,
                'message' => 'Seats assigned successfully.',
                'data' => $assignments, 
            ]);
        } catch (\InvalidArgumentException $e) { 
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400); 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while assigning seats.',
                'error' => $e->getMessage(),
            ], 500); 
        }
    }


    // 7. Search and Filter Users
    public function searchUsers(Request $request)
    {
        try {
            $users = $this->adminService->searchUsers($request);

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching users: ' . $e->getMessage(),
            ], 500); 
        }
    }

    // 8. Search and Filter Bookings
    public function searchBookings(Request $request)
    {
        try {
            $bookings = $this->adminService->searchBookings($request);

            return response()->json([
                'success' => true,
                'message' => 'Bookings retrieved successfully',
                'data' => $bookings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching bookings: ' . $e->getMessage(),
            ], 500); 
        }
    }
}