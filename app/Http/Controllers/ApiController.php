<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Palindot API",
 *         description="Api created for a URL Shortener that generates palindromic values.",
 *     )
 * )
 * @SWG\Definition(
 *     definition="ApiController",
 *     type="object",
 *     description="This is the base API class. It contains all the docs required by the other classes.",
 * )
 */
class ApiController extends Controller
{
    /**
     * @SWG\Definition(
     *     definition="sort",
     *     type="function",
     *     description="Sorts the data received.",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Sorted data, order by default by the creation time",
     *     @SWG\Schema(
     *         type="array",
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
     * @SWG\Definition(
     *     definition="pagination",
     *     type="function",
     *     description="Generates pagination for common dataTables.",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Pagination data",
     *     @SWG\Schema(
     *         type="array",
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
     * @SWG\Definition(
     *     definition="getCurrentTimestamp",
     *     type="function",
     *     description="Gets the current timestamp. The name is pretty much straightforward.",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Current timestamp",
     *     @SWG\Schema(
     *         type="string",
     *         format="date-time"
     *     )
     * )
     */
    protected function getCurrentTimestamp()
    {
        return Carbon::now();
    }
}
