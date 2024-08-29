<?php

namespace App\Http\Controllers;

use App\ServiceInterfaces\ModuleServiceInterface;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleServiceInterface $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function index()
    {
        return $this->moduleService->getAllModules();
    }

    public function show($id)
    {
        return $this->moduleService->getModuleById($id);
    }

    public function store(Request $request)
    {
        return $this->moduleService->createModule($request);
    }

    public function update(Request $request, $id)
    {
        return $this->moduleService->updateModule($request, $id);
    }

    public function destroy($id)
    {
        return $this->moduleService->deleteModule($id);
    }
}
