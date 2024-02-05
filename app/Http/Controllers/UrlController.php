<?php

namespace App\Http\Controllers;

use App\Models\Subset;
use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UrlRequest;
use App\Http\Resources\UrlResource;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Schema(
 *     schema="UrlController",
 *     type="object",
 *     description="Class with all the functions needed to manage URLs.",
 * )
 */
class UrlController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/urls",
     *     summary="Gets all the URLs of the user.",
     *     @OA\Response(
     *         response=200,
     *         description="List of URLs"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $userId = auth()->id();
    
        $sort = $this->sort($request);
    
        $urls = Url::join('subsets', 'urls.subset_id', '=', 'subsets.id')
            ->where('subsets.user_id', $userId)
            ->orderBy('urls.' . $sort['column'], $sort['order'])
            ->paginate((int) $request->get('perPage', 10));
    
    
        return response()->json(
            [
                'items' => UrlResource::collection($urls->items()),
                'pagination' => $this->pagination($urls),
            ]
        );
    }
    

    /**
     * Display the specified resource.
     *
     * @param Url $url url
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/urls/{url}",
     *     summary="Gets a specific URL.",
     *     @OA\Parameter(
     *         name="url",
     *         in="path",
     *         description="ID of the URL",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Specific URL"
     *     )
     * )
     */
    public function show(Url $url): JsonResponse
    {
        return response()->json(new UrlResource($url));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UrlRequest $request request
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/urls/create",
     *     summary="Creates a new URL.",
     *     @OA\RequestBody(
     *         description="URL parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UrlRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="URL created successfully"
     *     )
     * )
     */
    public static function store(UrlRequest $request): JsonResponse {
        
        $validated = $request->validated();

        $generatedUrl = Url::generateUrl($validated['base_url']);

        $urlData = [
            'base_url' => $validated['base_url'],
            'to_url' => $generatedUrl,
        ];

        $urlData['subset_id'] = $validated['subset_id'] ?? null;

        $createdUrl = Url::create($urlData);

        return response()->json([
            'id' => $createdUrl->id,
            'base_url' => $validated['base_url'],
            'to_url' => $generatedUrl,
            'subset_id' => $validated['subset_id'] ?? null,
            'message' => 'Url generated successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UrlRequest $request request
     * @param Url        $url     url
     *
     * @return JsonResponse
     * @OA\Put(
     *     path="/urls/{url}",
     *     summary="Updates a specific URL.",
     *     @OA\Parameter(
     *         name="url",
     *         in="path",
     *         description="ID of the URL",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\RequestBody(
     *         description="URL parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UrlRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="URL updated successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(UrlRequest $request, Url $url): JsonResponse
    {
        // Get the current user
        $user = auth()->user();
    
        // Check if the URL is part of a user's subset
        if ($url->subset->user_id == $user->id) {
            $url->update($request->validated());
            return response()->json([
                'message' => 'Data updated successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized. You are not the owner of this url address',
            ], 403);
        }
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param Url $url url
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/urls/{url}",
     *     summary="Deletes a specific URL.",
     *     @OA\Parameter(
     *         name="url",
     *         in="path",
     *         description="ID of the URL",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="URL deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(Url $url): JsonResponse
    {
        // Get the current user
        $user = auth()->user();
    
        // Check if the URL is part of a user's subset
        if ($url->subset->user_id == $user->id) {
            $url->delete();
            return response()->json([
                'message' => 'Data deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized. You are not the owner of this url address',
            ], 403);
        }
    }
}
