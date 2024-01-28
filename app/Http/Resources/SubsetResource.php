<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @SWG\Definition(
 *     definition="SubsetResource",
 *     type="object",
 *     description="This class contains the function to return the SubsetCollection as a JSON",
 *     @SWG\Property(
 *         property="id",
 *         type="integer",
 *         description="The ID of the subset"
 *     ),
 *     @SWG\Property(
 *         property="subset_name",
 *         type="string",
 *         description="The name of the subset"
 *     ),
 *     @SWG\Property(
 *         property="subset_descr",
 *         type="string",
 *         description="The description of the subset"
 *     ),
*     @SWG\Property(
 *         property="created_at",
 *         type="datetime"
 *         description="The creation date and time of the subset"
 *     ),
 *     @SWG\Property(
 *         property="updated_at",
 *         type="datetime"
 *         description="The last time you updated the subset"
 *     )
 * )
 */
class SubsetResource extends JsonResource
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
            'subset_name' => $this->subset_name,
            'subset_descr' => $this->subset_descr,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
