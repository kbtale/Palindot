<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     description="Requires the data necessary to register a user",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the user",
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="The email of the new user",
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="The password of the new user. It must be confirmed",
 *     )
 * )
 */

class RegisterRequest extends FormRequest
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
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
