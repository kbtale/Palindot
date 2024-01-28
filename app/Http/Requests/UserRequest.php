<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @SWG\Definition(
 *     definition="UserRequest",
 *     type="object",
 *     description="Requires the data the admin needs to register an user",
 *     @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="The username",
 *     ),
 *     @SWG\Property(
 *         property="email",
 *         type="string",
 *         description="The email of the new user",
 *     ),
 *     @SWG\Property(
 *         property="password",
 *         type="string",
 *         description="The password of the new user",
 *     )
 * )
 */
class UserRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users',
        ];
    }
}
