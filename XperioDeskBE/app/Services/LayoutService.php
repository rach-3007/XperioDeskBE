<?php

namespace App\Services;

use App\Models\CabinAndConferenceRoom;
use App\Models\Layout;
use App\Models\LayoutEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Seat;
use App\ServiceInterfaces\LayoutServiceInterface;

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
            'layout_name' => 'required|string|max:255',
            
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
        \DB::beginTransaction();
        try {
            // Create the Layout
            $layout = Layout::create([
                'layout_name' => $request->layout_name,
                'module_id' => $request->module_id,
                // Add any other layout-specific fields
            ]);
    
            // Create associated entities
            foreach ($request->entities as $entity) {
                switch ($entity['type']) {
                    case 'seat':
                        // Create a seat entity in the layout_entities table
                        $layoutEntity = LayoutEntity::create([
                            'layout_id' => $layout->id,
                            'type' => 'Seat',
                            'x-position' => $entity['x_position'],
                            'y-position' => $entity['y_position'],
                            'rotation' => $entity['rotation'],
                        ]);
    
                        // Now create the seat itself
                        Seat::create([
                            'seat_number' => $entity['seat_number'],
                            'module_id' => $request->module_id,
                            'layout_entity_id' => $layoutEntity->id,
                            'is_active' => true,
                            'status' => 'available',
                        ]);
                        break;
    
                    case 'cabin':
                        // Create a cabin entity in the layout_entities table
                        $layoutEntity = LayoutEntity::create([
                            'layout_id' => $layout->id,
                            'type' => 'Cabin',
                            'x-position' => $entity['x_position'],
                            'y-position' => $entity['y_position'],
                            'rotation' => $entity['rotation'],
                        ]);
    
                        // Now create the cabin or conference room
                        CabinAndConferenceRoom::create([
                            'layout_entity_id' => $layoutEntity->id,
                            'type' => 'cabin', // or 'conference_room' based on the entity type
                        ]);
                        break;
    
                    case 'conference_room':
                        // Create a conference room entity in the layout_entities table
                        $layoutEntity = LayoutEntity::create([
                            'layout_id' => $layout->id,
                            'type' => 'Conference',
                            'x-position' => $entity['x_position'],
                            'y-position' => $entity['y_position'],
                            'rotation' => $entity['rotation'],
                        ]);
    
                        // Now create the conference room
                        CabinAndConferenceRoom::create([
                            'layout_entity_id' => $layoutEntity->id,
                            'type' => 'conference_room',
                        ]);
                        break;
    
                    case 'partition':
                        // Create a partition entity in the layout_entities table
                        LayoutEntity::create([
                            'layout_id' => $layout->id,
                            'type' => 'Partition',
                            'x-position' => $entity['x_position'],
                            'y-position' => $entity['y_position'],
                            'rotation' => $entity['rotation'],
                        ]);
                        break;
    
                    case 'entrance':
                        // Create an entrance entity in the layout_entities table
                        LayoutEntity::create([
                            'layout_id' => $layout->id,
                            'type' => 'Entrance',
                            'x-position' => $entity['x_position'],
                            'y-position' => $entity['y_position'],
                            'rotation' => $entity['rotation'],
                        ]);
                        break;
                }
            }
    
            // Commit transaction
            \DB::commit();
    
            return $layout;
        } catch (\Exception $e) {
            // Rollback transaction in case of any error
            \DB::rollBack();
            throw $e;
        }
    }
    
}