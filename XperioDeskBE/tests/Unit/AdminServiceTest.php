<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $adminService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminService = new AdminService();
    }

    public function testGetAllUsers()
    {
        User::factory()->count(5)->create();
        $users = $this->adminService->getAllUsers();
        $this->assertCount(5, $users);
    }

    public function testGetUserBookings()
    {
        $user = User::factory()->create();
        $seat = Seat::factory()->create();
        Booking::factory()->create(['user_id' => $user->id, 'seat_id' => $seat->id]);
        $bookings = $this->adminService->getUserBookings($user->id);
        $this->assertCount(1, $bookings);
        $this->assertEquals($seat->id, $bookings->first()->seat->id);
    }

    public function testGetUserBookingsWithNoBookings()
    {
        $user = User::factory()->create();
        $bookings = $this->adminService->getUserBookings($user->id);
        $this->assertCount(0, $bookings);
    }

    public function testCancelBooking()
    {
        $seat = Seat::factory()->create(['status' => 'booked']);
        $booking = Booking::factory()->create(['seat_id' => $seat->id, 'start_date' => now()->subDays(1)]);
        $this->adminService->cancelBooking($booking->id);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
        $this->assertDatabaseHas('seats', ['id' => $seat->id, 'status' => 'available']);
    }

    public function testCancelNonExistentBooking()
    {
        $result = $this->adminService->cancelBooking(999);
        $this->assertFalse($result->getData()->success);
        $this->assertEquals('Booking not found.', $result->getData()->message);
    }

    public function testAssignSeatSuccess()
    {
        $user = User::factory()->create();
        $seat = Seat::factory()->create();
        $request = new Request([
            'user_id' => $user->id,
            'layout_entity_id' => $seat->layout_entity_id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'booked_by' => $user->id
        ]);

        $response = $this->adminService->assignSeat($request);

        $this->assertTrue($response->getData()->success);
        $this->assertDatabaseHas('bookings', [
            'seat_id' => $seat->id,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('seats', [
            'id' => $seat->id,
            'status' => 'booked',
            'booked_by_user_id' => $user->id,
        ]);
    }

    public function testAssignSeatFailureSeatAlreadyBooked()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $seat = Seat::factory()->create(['status' => 'booked']);
        Booking::factory()->create(['seat_id' => $seat->id, 'user_id' => $user1->id]);

        $request = new Request([
            'user_id' => $user2->id,
            'layout_entity_id' => $seat->layout_entity_id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'booked_by' => $user2->id
        ]);

        $response = $this->adminService->assignSeat($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Seat is already booked.', $response->getData()->message);
    }

    public function testAssignSeatFailureInvalidUserId()
    {
        $seat = Seat::factory()->create();
        $request = new Request([
            'user_id' => 999, // Non-existent user ID
            'layout_entity_id' => $seat->layout_entity_id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'booked_by' => 999
        ]);

        $response = $this->adminService->assignSeat($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('User not found.', $response->getData()->message);
    }

    public function testBulkCancelBookings()
    {
        $bookings = Booking::factory()->count(3)->create(['start_date' => now()->subDays(1)]);
        $request = new Request([
            'booking_ids' => $bookings->pluck('id')->toArray()
        ]);

        $response = $this->adminService->bulkCancelBookings($request);

        foreach ($bookings as $booking) {
            $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'canceled']);
            $this->assertDatabaseHas('seats', ['id' => $booking->seat->id, 'status' => 'available']);
        }
    }

    public function testBulkCancelBookingsFailureInvalidBookingIds()
    {
        $request = new Request([
            'booking_ids' => [999, 1000] // Non-existent booking IDs
        ]);

        $response = $this->adminService->bulkCancelBookings($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Some bookings not found.', $response->getData()->message);
    }

    public function testBulkAssignSeatsSuccess()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $seat1 = Seat::factory()->create();
        $seat2 = Seat::factory()->create();

        $request = new Request([
            'assignments' => [
                [
                    'seat_id' => $seat1->id,
                    'user_id' => $user1->id,
                    'booked_by' => $user1->id,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(2)->toDateString(),
                ],
                [
                    'seat_id' => $seat2->id,
                    'user_id' => $user2->id,
                    'booked_by' => $user2->id,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(2)->toDateString(),
                ],
            ]
        ]);

        $response = $this->adminService->bulkAssignSeats($request);

        $this->assertCount(2, $response);

        $this->assertDatabaseHas('bookings', [
            'seat_id' => $seat1->id,
            'user_id' => $user1->id,
        ]);

        $this->assertDatabaseHas('bookings', [
            'seat_id' => $seat2->id,
            'user_id' => $user2->id,
        ]);
    }

    public function testBulkAssignSeatsFailureOverlappingDates()
    {
        $user1 = User::factory()->create();
        $seat1 = Seat::factory()->create();
        Booking::factory()->create([
            'seat_id' => $seat1->id,
            'user_id' => $user1->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
        ]);

        $request = new Request([
            'assignments' => [
                [
                    'seat_id' => $seat1->id,
                    'user_id' => $user1->id,
                    'booked_by' => $user1->id,
                    'start_date' => now()->addDay()->toDateString(),
                    'end_date' => now()->addDays(2)->toDateString(),
                ],
            ]
        ]);

        $response = $this->adminService->bulkAssignSeats($request);

        $this->assertFalse($response[0]['success']);
        $this->assertEquals('Seat is already booked for the selected dates.', $response[0]['message']);
    }

    public function testBulkAssignSeatsFailureSeatAlreadyBooked()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $seat = Seat::factory()->create(['status' => 'booked']);
        Booking::factory()->create(['seat_id' => $seat->id, 'user_id' => $user1->id]);

        $request = new Request([
            'assignments' => [
                [
                    'seat_id' => $seat->id,
                    'user_id' => $user2->id,
                    'booked_by' => $user2->id,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(2)->toDateString(),
                ],
            ]
        ]);

        $response = $this->adminService->bulkAssignSeats($request);

        $this->assertFalse($response[0]['success']);
        $this->assertEquals('Seat is already booked.', $response[0]['message']);
    }

    public function testBulkAssignSeatsFailureInvalidUserIds()
    {
        $seat1 = Seat::factory()->create();
        $request = new Request([
            'assignments' => [
                [
                    'seat_id' => $seat1->id,
                    'user_id' => 999, // Non-existent user ID
                    'booked_by' => 999,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(2)->toDateString(),
                ],
            ]
        ]);

        $response = $this->adminService->bulkAssignSeats($request);

        $this->assertFalse($response[0]['success']);
        $this->assertEquals('User not found.', $response[0]['message']);
    }

    public function testBulkCancelBookingsWithMixOfValidAndInvalidBookingIds()
    {
        $validBooking = Booking::factory()->create(['start_date' => now()->subDays(1)]);
        $invalidBookingId = 999;

        $request = new Request([
            'booking_ids' => [$validBooking->id, $invalidBookingId]
        ]);

        $response = $this->adminService->bulkCancelBookings($request);

        $this->assertFalse($response->getData()->success);
        $this->assertEquals('Some bookings not found.', $response->getData()->message);
        
        // Check if the valid booking is canceled
        $this->assertDatabaseHas('bookings', ['id' => $validBooking->id, 'status' => 'canceled']);
    }
}
