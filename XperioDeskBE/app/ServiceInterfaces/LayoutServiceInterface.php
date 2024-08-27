<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface LayoutServiceInterface
{
    public function getAllLayouts();
    public function getLayoutById($id);
    public function createLayout(Request $request);
    public function updateLayout(Request $request, $id);
    public function deleteLayout($id);
    public function createLayoutWithEntities(Request $request);
}
