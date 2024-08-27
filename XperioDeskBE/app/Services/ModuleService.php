<?php

namespace App\Services;

use App\Models\Module;
use App\ServiceInterfaces\ModuleServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ModuleService implements ModuleServiceInterface
{
    public function getAllModules()
    {
        return Module::all();
    }

    public function getModuleById($id)
    {
        return Module::findOrFail($id);
    }

    public function createModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $module = Module::create($validator->validated());

        return response()->json([
            'message' => 'Module successfully created',
            'module' => $module
        ], 201);
    }

    public function updateModule(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $module = Module::findOrFail($id);
        $module->update($validator->validated());

        return response()->json([
            'message' => 'Module successfully updated',
            'module' => $module
        ], 200);
    }

    public function deleteModule($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return response()->json([
            'message' => 'Module successfully deleted'
        ], 200);
    }
}
