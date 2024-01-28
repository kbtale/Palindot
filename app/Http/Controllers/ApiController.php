<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Palindot API",
 *         description="User operations",
 *     )
 * )
 * @SWG\Definition(
 *     definition="ApiController",
 *     type="object",
 *     description="This is the base API class",
 * )
 */
class ApiController extends Controller
{
    /**
     * DataTable sorting for common resources
     *
     * @param mixed $request
     *
     * @return array
     * @SWG\Post(
     *     path="/sort",
     *     description="Sorts the data received",
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         description="Sorting parameters",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/SortRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Sorted data"
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
     * Generates pagination for common dataTables
     *
     * @param \Illuminate\Database\Eloquent\Collection $items
     *
     * @return array
     * @SWG\Get(
     *     path="/pagination",
     *     description="Generates pagination for common dataTables",
     *     @SWG\Response(
     *         response=200,
     *         description="Pagination data"
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
     * @SWG\Get(
     *     path="/timestamp",
     *     description="Gets the current timestamp",
     *     @SWG\Response(
     *         response=200,
     *         description="Current timestamp"
     *     )
     * )
     */
    protected function getCurrentTimestamp()
    {
        return Carbon::now();
    }
}
