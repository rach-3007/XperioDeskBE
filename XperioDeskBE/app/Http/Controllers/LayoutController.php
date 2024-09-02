<?php
 
namespace App\Http\Controllers;
use App\ServiceInterfaces\LayoutServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;


/**
 * @OA\Info(title="Layout API", version="1.0")
 */
class LayoutController extends Controller
{
    protected $layoutService;
    

    public function __construct(LayoutServiceInterface $layoutService)
    {
        $this->layoutService = $layoutService;
    }
    /**
     * @OA\Get(
     *     path="/layouts",
     *     summary="Get all layouts",
     *     tags={"Layouts"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all layouts",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Layout"))
     *     )
     * )
     */
    public function index()
    {
        return $this->layoutService->getAllLayouts();
    }
    /**
     * @OA\Get(
     *     path="/layouts/{id}",
     *     summary="Get a specific layout by ID",
     *     tags={"Layouts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Layout details",
     *         @OA\JsonContent(ref="#/components/schemas/Layout")
     *     )
     * )
     */
    public function show($id)
    {
        return $this->layoutService->getLayoutById($id);
    }
    /**
     * @OA\Post(
     *     path="/layouts",
     *     summary="Create a new layout",
     *     tags={"Layouts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Layout")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Layout created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Layout")
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->layoutService->createLayout($request);
    }
    /**
     * @OA\Put(
     *     path="/layouts/{id}",
     *     summary="Update an existing layout",
     *     tags={"Layouts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Layout")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Layout updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Layout")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        return $this->layoutService->updateLayout($request, $id);
    }
    /**
     * @OA\Delete(
     *     path="/layouts/{id}",
     *     summary="Delete a layout",
     *     tags={"Layouts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Layout deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Layout")
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->layoutService->deleteLayout($id);
    }
    /**
     * @OA\Post(
     *     path="/layouts/create-with-entities",
     *     summary="Create a layout with entities",
     *     tags={"Layouts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="layout_name", type="string"),
     *             @OA\Property(
     *                 property="entities",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="type", type="string", enum={"seat", "cabin", "conference_room"}),
     *                     @OA\Property(property="position", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Layout and entities created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="layout", ref="#/components/schemas/Layout")
     *         )
     *     )
     * )
     */
    public function createLayoutWithEntities(Request $request)
    {
      
        $layout = $this->layoutService->createLayoutWithEntities($request);

        return response()->json([
            'message' => 'Layout created successfully with all related entities',
            'layout' => $layout
        ], 201);
    }
    public function getLayoutWithEntities($id)
    {
        $layout = $this->layoutService->getLayoutWithEntities($id);

        return response()->json([
            'layout' => $layout
        ], 200);
    }
}