<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubsetRequest;
use App\Http\Resources\SubsetCollection;
use App\Models\Subset;
use Illuminate\Http\Request;
use App\Models\Url;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SubsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $sort = $this->sort($request);
        $subsets = Subset::filter($request->all())
            ->orderBy($sort['column'], $sort['order'])
            ->paginate((int) $request->get('perPage', 10));
        return response()->json(
            [
                'items' => SubsetCollection::collection($subsets->items()),
                'pagination' => $this->pagination($subsets),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param      \App\Models\Subset  $subset      The subset to be shown
     *
     * @return     JsonResponse                     The json response.
     */
    public function show(Url $subset): JsonResponse
    {
        return response()->json(new SubsetCollection($subset));
    }

    /**
     * Function to store the shortened Urls
     * 
     * 
     */
    public static function store(SubsetRequest $request): JsonResponse {
        $validated = $request->validate();

        Subset::create([
            'subset_name' => $validated['subset_name'],
            'subset_descr' => $validated['subset_descr'],
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'subset_name' => $validated['subset_name'],
            'subset_descr' => $validated['subset_descr'],
            'message' => 'Subset Created Successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param      \App\Http\Requests\SubsetRequest  $request       The request
     * @param      \App\Models\Subset                $subset        The subset to be updated
     *
     * @return     JsonResponse                                  The json response.
     */
    public function update(SubsetRequest $request, Subset $subset): JsonResponse
    {
        $subset->update($request->validated());
        return response()->json([
            'message' => __('Data updated successfully'),
        ]);
    }

    /**
     * Destroys the given Url.
     *
     * @param      \App\Models\SubsetRequest  $subset  The subset to be deleted
     *
     * @return     JsonResponse              The json response.
     */
    public function destroy(SubsetRequest $subset): JsonResponse
    {
        $subset->delete();
        return response()->json(['message' => __('Data removed successfully')]);
    }
}
