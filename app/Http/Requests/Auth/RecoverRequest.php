<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="RecoverRequest",
 *     type="object",
 *     description="Requires the data necessary to recover an account. You only need one of them",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the user",
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="The email of the user",
 *     )
 * )
 */
class RecoverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
