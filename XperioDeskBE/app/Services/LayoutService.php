<?php

namespace App\Services;

use App\Models\CabinsAndConferenceRoom;
use App\Models\Layout;
use Illuminate\Support\Facades\DB;
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
         // Validate request data
         $request->validate([
            'entities' => 'required|array',
            'entities.*.id' => 'required|integer|exists:layout_entities,id',
            'entities.*.x_position' => 'required|numeric',
            'entities.*.y_position' => 'required|numeric',
            'entities.*.rotation' => 'nullable|numeric',
        ]);

        try {
            // Fetch the layout
            $layout = Layout::findOrFail($id);

            // Update positions of entities
            foreach ($request->entities as $entityData) {
                $entity = $layout->entities()->find($entityData['id']);
                if ($entity) {
                    $entity->x_position = $entityData['x_position'];
                    $entity->y_position = $entityData['y_position'];
                    $entity->rotation = $entityData['rotation'] ?? $entity->rotation; // Optional: Update rotation if provided
                    $entity->save();
                }
            }

            return response()->json(['message' => 'Layout updated successfully!'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update layout', 'message' => $e->getMessage()], 500);
        }
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
    // Validate the request data
    $validatedData = $request->validate([
        'layout_name' => 'required|string|max:255',
        'module_id' => 'required|exists:modules,id',
        'entities' => 'required|array',
        'entities.*.type' => 'required|string',
        'entities.*.x_position' => 'required|numeric',
        'entities.*.y_position' => 'required|numeric',
        'entities.*.rotation' => 'required|numeric',
        'entities.*.seat_number' => 'sometimes|string',
        'access_dus' => 'required|array',
        'access_dus.*' => 'exists:du,id',
    ]);

    // Create the Layout
    $layout = Layout::create([
        'layout_name' => $validatedData['layout_name'],
        'module_id' => $validatedData['module_id'],
    ]);

    // Create associated entities
    foreach ($validatedData['entities'] as $entity) {
        switch ($entity['type']) {
            case 'seat':
                // Create a seat entity in the layout_entities table
                $layoutEntity = LayoutEntity::create([
                    'layout_id' => $layout->id,
                    'type' => 'Seat',
                    'x_position' => $entity['x_position'], 
                    'y_position' => $entity['y_position'], 
                    'rotation' => $entity['rotation'],     
                ]);

                // Now create the seat itself
                Seat::create([
                    'seat_number' => $entity['seat_number'],
                    'module_id' => $validatedData['module_id'],
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
                'x_position' => $entity['x_position'], 
                'y_position' => $entity['y_position'], 
                'rotation' => $entity['rotation'],     
                ]);

                // Now create the cabin or conference room
                CabinsAndConferenceRoom::create([
                    'layout_entity_id' => $layoutEntity->id,
                    'type' => 'cabin', // or 'conference_room' based on the entity type
                ]);
                break;

            case 'conference_room':
                // Create a conference room entity in the layout_entities table
                $layoutEntity = LayoutEntity::create([
                    'layout_id' => $layout->id,
                'type' => 'Conference',
                'x_position' => $entity['x_position'], 
                'y_position' => $entity['y_position'], 
                'rotation' => $entity['rotation'],     
                ]);

                // Now create the conference room
                CabinsAndConferenceRoom::create([
                    'layout_entity_id' => $layoutEntity->id,
                    'type' => 'conference_room',
                ]);
                break;

            case 'partition':
                // Create a partition entity in the layout_entities table
                LayoutEntity::create([
                    'layout_id' => $layout->id,
                'type' => 'Partition',
                'x_position' => $entity['x_position'], 
                'y_position' => $entity['y_position'], 
                'rotation' => $entity['rotation'],     
                ]);
                break;

            case 'entrance':
                // Create an entrance entity in the layout_entities table
                LayoutEntity::create([
                    'layout_id' => $layout->id,
                'type' => 'Entrance',
                'x_position' => $entity['x_position'],
                'y_position' => $entity['y_position'],
                'rotation' => $entity['rotation'],    
                ]);
                break;
        }
    }

    foreach ($validatedData['access_dus'] as $duId) {
        DB::table('du_module_access')->insert([
            'du_id' => $duId,
            'module_id' => $validatedData['module_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return response()->json($layout, 201);
}

public function getLayoutWithEntities($id)
{
    $layout = Layout::with('layoutEntities')->findOrFail($id);
 
    $layout->layoutEntities->each(function ($entity) {
        switch ($entity->type) {
            case 'Seat':
                $entity->seat = Seat::where('layout_entity_id', $entity->id)->first();
                break;
           
            case 'Cabin': 
                $entity->cabinsAndConferenceRoom = CabinsAndConferenceRoom::where('layout_entity_id', $entity->id)->first();
            break;
            case 'Conference Room':
                $entity->cabinsAndConferenceRoom = CabinsAndConferenceRoom::where('layout_entity_id', $entity->id)->first();
                break;
 
            case 'Partition':
                $entity->partitionDetails = LayoutEntity::where('id', $entity->id)->first();
                break;
 
            case 'Entrance':
                $entity->entranceDetails = LayoutEntity::where('id', $entity->id)->first();
                break;
 
            default:
                $entity->additionalDetails = 'No additional details available';
                break;
        }
    });
 


    return response()->json($layout, 200);
}    
}