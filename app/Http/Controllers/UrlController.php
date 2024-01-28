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
 * @SWG\Definition(
 *     definition="AuthController",
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
     * @SWG\Get(
     *     path="/urls",
     *     description="Gets all the URLs of the user.",
     *     @SWG\Response(
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
     * @SWG\Get(
     *     path="/urls/{url}",
     *     description="Gets a specific URL.",
     *     @SWG\Parameter(
     *         name="url",
     *         in="path",
     *         description="ID of the URL",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
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
     * @SWG\Post(
     *     path="/urls/create",
     *     description="Creates a new URL.",
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         description="URL parameters",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UrlRequest")
     *     ),
     *     @SWG\Response(
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
     * @SWG\Put(
     *     path="/urls/{url}",
     *     description="Updates a specific URL.",
     *     @SWG\Parameter(
     *         name="url",
     *         in="path",
     *         description="ID of the URL",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         description="URL parameters",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UrlRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="URL updated successfully"
     *     ),
     *     @SWG\Response(
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
     * @SWG\Delete(
     *     path="/urls/{url}",
     *     description="Deletes a specific URL.",
     *     @SWG\Parameter(
     *         name="url",
     *         in="path",
     *         description="ID of the URL",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="URL deleted successfully"
     *     ),
     *     @SWG\Response(
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
