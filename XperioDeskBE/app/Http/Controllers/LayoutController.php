<?php

namespace App\Http\Controllers;

use App\ServiceInterfaces\LayoutServiceInterface;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    protected $layoutService;

    public function __construct(LayoutServiceInterface $layoutService)
    {
        $this->layoutService = $layoutService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module_id' => 'required|integer',
            'name' => 'required|string',
            'entities' => 'required|array',
        ]);

        $layout = $this->layoutService->createLayout($data);
        return response()->json($layout, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'entities' => 'required|array',
        ]);

        $layout = $this->layoutService->updateLayout($id, $data);
        return response()->json($layout);
    }

    public function show($id)
    {
        $layout = $this->layoutService->getLayout($id);
        return response()->json($layout);
    }

    public function destroy($id)
    {
        $this->layoutService->deleteLayout($id);
        return response()->json(['message' => 'Layout deleted successfully']);
    }
}
