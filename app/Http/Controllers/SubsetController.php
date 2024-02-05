<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubsetRequest;
use App\Http\Resources\SubsetResource;
use App\Models\Subset;
use Illuminate\Http\Request;
use App\Models\Url;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *     schema="SubsetController",
 *     type="object",
 *     description="Class with all the functions needed to manage subsets.",
 * )
 */
class SubsetController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/subsets",
     *     summary="Gets the list of subsets of the user.",
     *     @OA\Response(
     *         response=200,
     *         description="List of subsets"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $sort = $this->sort($request);

        $userId = $request->user()->id;

        $subsets = Subset::where('user_id', $userId)
            ->orderBy($sort['column'], $sort['order'])
            ->paginate((int) $request->get('perPage', 10));
        return response()->json(
            [
                'items' => SubsetResource::collection($subsets->items()),
                'pagination' => $this->pagination($subsets),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Subset $subset subset
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/subsets/{id}",
     *     summary="Gets a specific subset.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the subset",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Specific subset"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show(Subset $subset): JsonResponse
    {
        // Get the current user
        $user = auth()->user();
    
        // Check if the Subset is a Subset of the current user
        if ($subset->user_id == $user->id) {
            $urls = $subset->urls;
            return response()->json([
                'subset' => $subset,
                'urls' => $urls,
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized. You are not the user',
            ], 403);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param SubsetRequest $request request
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/subsets",
     *     summary="Creates a new subset.",
     *     @OA\RequestBody(
     *         description="Subset parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SubsetRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subset created successfully"
     *     )
     * )
     */
    public static function store(SubsetRequest $request): JsonResponse {
        $validated = $request->validated();

        $createdSubset = Subset::create([
            'subset_name' => $validated['subset_name'],
            'subset_descr' => $validated['subset_descr'],
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'id' => $createdSubset->id,
            'subset_name' => $validated['subset_name'],
            'subset_descr' => $validated['subset_descr'],
            'message' => 'Subset Created Successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubsetRequest $request request
     * @param Subset        $subset  subset
     *
     * @return JsonResponse
     * @OA\Put(
     *     path="/subsets/{id}",
     *     summary="Updates a specific subset.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the subset",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\RequestBody(
     *         description="Subset parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SubsetRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subset updated successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(SubsetRequest $request, Subset $subset): JsonResponse
    {
        // Get the current user
        $user = auth()->user();
    
        // Check if the Subset is a Subset of the current user
        if ($subset->user_id == $user->id) {
            $subset->update($request->validated());
            return response()->json([
                'message' => 'Data updated successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized. You are not the user',
            ], 403);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param Subset $subset subset
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/subsets/{id}",
     *     summary="Deletes a specific subset.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the subset",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subset deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(Subset $subset): JsonResponse
    {
        // Get the current user
        $user = auth()->user();
    
        // Check if the Subset is a Subset of the current user
        if ($subset->user_id == $user->id) {
            $subset->delete();
            return response()->json([
                'message' => 'Data deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized. You are not the user',
            ], 403);
        }
    }
}
