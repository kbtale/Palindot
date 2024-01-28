<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @SWG\Definition(
 *     definition="SubsetResource",
 *     type="object",
 *     description="This class contains the function to return the Url data as a JSON",
 *     @SWG\Property(
 *         property="id",
 *         type="integer",
 *         description="The ID of the url"
 *     ),
 *     @SWG\Property(
 *         property="base_url",
 *         type="string",
 *         description="The original url"
 *     ),
 *     @SWG\Property(
 *         property="to_url",
 *         type="string",
 *         description="The shortened url"
 *     ),
 *     @SWG\Property(
 *         property="subset_id",
 *         type="integer",
 *         description="The id of the parent subset"
 *     ),
 *     @SWG\Property(
 *         property="created_at",
 *         type="datetime"
 *         description="The creation date and time of the url"
 *     ),
 *     @SWG\Property(
 *         property="updated_at",
 *         type="datetime"
 *         description="The last time you updated the url"
 *     )
 * )
 */
class UrlResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'base_url' => $this->base_url,
            'to_url' => $this->to_url,
            'subset_id' => $this->subset_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
