<?php
 
namespace App\Http\Controllers;
<<<<<<< HEAD
 
=======

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
use App\ServiceInterfaces\LayoutServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
<<<<<<< HEAD
 
 
=======


>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
/**
 * @OA\Info(title="Layout API", version="1.0")
 */
class LayoutController extends Controller
{
    protected $layoutService;
<<<<<<< HEAD
   
 
=======
    

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
    public function __construct(LayoutServiceInterface $layoutService)
    {
        $this->layoutService = $layoutService;
    }
<<<<<<< HEAD
 
=======

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
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
<<<<<<< HEAD
 
=======

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
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
<<<<<<< HEAD
 
=======

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
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
<<<<<<< HEAD
 
=======

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
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
<<<<<<< HEAD
 
=======

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
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
<<<<<<< HEAD
 
=======

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
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
<<<<<<< HEAD
     
        $layout = $this->layoutService->createLayoutWithEntities($request);
 
=======
      
        $layout = $this->layoutService->createLayoutWithEntities($request);

>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
        return response()->json([
            'message' => 'Layout created successfully with all related entities',
            'layout' => $layout
        ], 201);
    }
<<<<<<< HEAD
    public function getLayoutWithEntities($id)
    {
        $layout = $this->layoutService->getLayoutWithEntities($id);

        return response()->json([
            'layout' => $layout
        ], 200);
    }
=======
>>>>>>> d20f93d0f7fc961e14c0db212b139643f691ce99
}