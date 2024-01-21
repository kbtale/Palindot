<?php

namespace App\Http\Controllers;

use App\Models\UrlSubset;
use App\Http\Requests\StoreUrlSubsetRequest;
use App\Http\Requests\UpdateUrlSubsetRequest;

class UrlSubsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUrlSubsetRequest $request)
    {
        $validated = $request->validate();
    
        $UrlSubset = UrlSubset::create([
            'subset_id' => $validated['subset_id'],
            'url_id' => $validated['url_id'],
        ]);
    
        return response()->json([
            'message' => 'Link added to subset successfully',
            'UrlSubset' => $UrlSubset,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(UrlSubset $link_subset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUrlSubsetRequest $request, UrlSubset $link_subset)
    {
        $link_subset->update($request->validated());
        return response()->json([
            'message' => 'Data updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UrlSubset $link_subset)
    {
        $link_subset->delete();
        return response()->json(['message' => 'Data removed successfully']);
    }
}
