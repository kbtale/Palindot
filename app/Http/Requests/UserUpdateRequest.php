<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @SWG\Definition(
 *     definition="UserUpdateRequest",
 *     type="object",
 *     description="Requires the data necessary to update a user.",
 *     @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the user",
 *     ),
 *     @SWG\Property(
 *         property="password",
 *         type="string",
 *         description="The password of the new user. It must be confirmed",
 *     )
 * )
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'password' => 'required|string|min:8',
        ];
    }
}
