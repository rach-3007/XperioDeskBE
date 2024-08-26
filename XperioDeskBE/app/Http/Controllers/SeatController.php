<?php

namespace App\Http\Controllers;

use App\ServiceInterfaces\SeatServiceInterface;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    protected $seatService;

    public function __construct(SeatServiceInterface $seatService)
    {
        $this->seatService = $seatService;
    }

    public function index()
    {
        return response()->json($this->seatService->listAllSeats());
    }

    public function show($id)
    {
        return response()->json($this->seatService->getSeatDetails($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'seat_number' => 'required|string',
            'module_id' => 'required|exists:modules,id',
            'layout_entities_id' => 'required|exists:layout_entities,id',
            // Add other validation rules as needed
        ]);

        return response()->json($this->seatService->createSeat($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'seat_number' => 'sometimes|string',
            'module_id' => 'sometimes|exists:modules,id',
            'layout_entities_id' => 'sometimes|exists:layout_entities,id',
            // Add other validation rules as needed
        ]);

        return response()->json($this->seatService->updateSeat($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->seatService->deleteSeat($id), 204);
    }

    public function restore($id)
{
    return response()->json($this->seatService->restoreSeat($id));
}

    public function checkAvailability($id)
    {
        return response()->json(['available' => $this->seatService->checkSeatAvailability($id)]);
    }
}
