<?php

namespace App\Http\Controllers;

use App\Models\Subset;
use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UrlRequest;
use App\Http\Resources\UrlCollection;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $sort = $this->sort($request);
        $urls = Url::filter($request->all())
            ->orderBy($sort['column'], $sort['order'])
            ->paginate((int) $request->get('perPage', 10));
        return response()->json(
            [
                'items' => UrlCollection::collection($urls->items()),
                'pagination' => $this->pagination($urls),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param      \App\Models\Url  $url     The url to be shown
     *
     * @return     JsonResponse              The json response.
     */
    public function show(Url $url): JsonResponse
    {
        return response()->json(new UrlCollection($url));
    }

    /**
     * Function to store the shortened Urls
     * 
     * 
     */
    public static function store(UrlRequest $request): JsonResponse {
        $validated = $request->validate();
        $generatedUrl = Url::generateUrl($validated['base_url']);

        Url::create([
            'base_url' => $validated['base_url'],
            'to_url' => $generatedUrl,
            'subset_id' => $validated['subset_id']
        ]);

        return response()->json([
            'base_url' => $validated['base_url'],
            'to_url' => $generatedUrl,
            'subset_id' => $validated['subset_id'],
            'message' => 'Url Generated Successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param      \App\Http\Requests\UrlRequest  $request       The request
     * @param      \App\Models\Url                $Url           The url to be updated
     *
     * @return     JsonResponse                                  The json response.
     */
    public function update(UrlRequest $request, Url $url): JsonResponse
    {
        $url->update($request->validated());
        return response()->json([
            'message' => 'Data updated successfully',
        ]);
    }

    /**
     * Destroys the given Url.
     *
     * @param      \App\Models\UrlRequest  $url  The url to be deleted
     *
     * @return     JsonResponse              The json response.
     */
    public function destroy(UrlRequest $url): JsonResponse
    {
        $url->delete();
        return response()->json(['message' => __('Data removed successfully')]);
    }
}
