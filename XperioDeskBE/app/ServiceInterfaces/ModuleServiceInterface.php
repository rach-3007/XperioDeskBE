<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface ModuleServiceInterface
{
    public function getAllModules();
    public function getModuleById($id);
    public function createModule(Request $request);
    public function updateModule(Request $request, $id);
    public function deleteModule($id);
}
