<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\OpenApi(
 *     servers={
 *         @OA\Server(
 *             url="/api/v1",
 *             description="Palindot API"
 *         )
 *     },
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Palindot API",
 *         description="Api created for a URL Shortener that generates palindromic values.",
 *     )
 * )
 * @OA\Schema(
 *     schema="ApiController",
 *     type="object",
 *     description="This is the base API class. It contains all the docs required by the other classes.",
 * )
 */

class ApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/sort",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/sort")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sorted data, order by default by the creation time",
     *         @OA\JsonContent(type="array")
     *     )
     * )
     */
    protected function sort($request): array
    {
        return $request->get('sort', json_decode(json_encode(['order' => 'asc', 'column' => 'created_at']),
            true,
            512,
            JSON_THROW_ON_ERROR
        ));
    }

    /**
     * @OA\Post(
     *     path="/sort",
     *     summary="Sorts the data received",
     *     @OA\RequestBody(
     *         description="Sort parameters",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="order",
     *                 type="string",
     *                 description="The order to sort by",
     *                 example="asc"
     *             ),
     *             @OA\Property(
     *                 property="column",
     *                 type="string",
     *                 description="The column to sort by",
     *                 example="created_at"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sorted data, order by default by the creation time",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/YourDataModel")
     *         )
     *     )
     * )
     */
    protected function pagination($items): array
    {
        return [
            'currentPage' => $items->currentPage(),
            'perPage' => $items->perPage(),
            'total' => $items->total(),
            'totalPages' => $items->lastPage(),
        ];
    }

    /**
     * @OA\Get(
     *     path="/getCurrentTimestamp",
     *     summary="Gets the current timestamp. The name is pretty much straightforward.",
     *     @OA\Response(
     *         response=200,
     *         description="Current timestamp",
     *         @OA\JsonContent(
     *             type="string",
     *             format="date-time"
     *         )
     *     )
     * )
     */
    protected function getCurrentTimestamp()
    {
        return Carbon::now();
    }
}
