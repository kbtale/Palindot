<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @SWG\Definition(
 *     definition="UserResource",
 *     type="object",
 *     description="This class contains the function to return the user data as a JSON",
 *     @SWG\Property(
 *         property="id",
 *         type="integer",
 *         description="The ID of the user"
 *     ),
 *     @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the user"
 *     ),
 *     @SWG\Property(
 *         property="email",
 *         type="string",
 *         description="The email of the subset"
 *     ),
 *     @SWG\Property(
 *         property="avatar",
 *         type="string",
 *         description="The avatar of the user"
 *     ),
 *     @SWG\Property(
 *         property="gravatar",
 *         type="string",
 *         description="Gravatar returned if the user has not an avatar"
 *     ),
 *     @SWG\Property(
 *         property="created_at",
 *         type="datetime"
 *         description="The creation date and time of the user"
 *     ),
 *     @SWG\Property(
 *         property="updated_at",
 *         type="datetime"
 *         description="The last time you updated the user"
 *     )
 */
class UserResource extends JsonResource
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
        'name' => $this->name,
        'email' => $this->email,
        'avatar' => $this->getAvatar(),
        'gravatar' => $this->getGravatar(),
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        ];
    }
}
