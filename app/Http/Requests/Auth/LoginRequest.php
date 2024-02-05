<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     description="Requires the data necessary to log in",
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="The email of the user",
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="The password of the user",
 *     )
 * )
 */
class LoginRequest extends FormRequest
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
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }
}
