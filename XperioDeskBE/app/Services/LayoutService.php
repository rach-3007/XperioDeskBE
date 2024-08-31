<?php

namespace App\Services;

use App\Models\CabinAndConferenceRoom;
use App\Models\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Seat;
use App\ServiceInterfaces\LayoutServiceInterface;
use Illuminate\Support\Facades\DB;

class LayoutService implements LayoutServiceInterface
{
    public function getAllLayouts()
    {
        return Layout::all();
    }

    public function getLayoutById($id)
    {
        return Layout::findOrFail($id);
    }

    public function createLayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $layout = Layout::create($validator->validated());

        return response()->json([
            'message' => 'Layout successfully created',
            'layout' => $layout
        ], 201);
    }

    public function updateLayout(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $layout = Layout::findOrFail($id);
        $layout->update($validator->validated());

        return response()->json([
            'message' => 'Layout successfully updated',
            'layout' => $layout
        ], 200);
    }

    public function deleteLayout($id)
    {
        $layout = Layout::findOrFail($id);
        $layout->delete();

        return response()->json([
            'message' => 'Layout successfully deleted'
        ], 200);
    }

    public function createLayoutWithEntities(Request $request)
    {
        // Start transaction to ensure atomic operation
        DB::beginTransaction();
        try {
            // Create the Layout
            $layout = Layout::create([
                'name' => $request->layout_name,
                // Add any other layout-specific fields
            ]);

            // Create associated entities
            foreach ($request->entities as $entity) {
                switch ($entity['type']) {
                    case 'seat':
                        Seat::create([
                            'layout_id' => $layout->id,
                            // Add any other seat-specific fields
                        ]);
                        break;
                    case 'cabin':
                        CabinAndConferenceRoom::create([
                            'layout_id' => $layout->id,
                            // Add any other cabin-specific fields
                        ]);
                        break;
                        
                }
            }

            // Commit transaction
            DB::commit();

            return $layout;
        } catch (\Exception $e) {
            // Rollback transaction in case of any error
            DB::rollBack();
            throw $e;
        }
    }
}