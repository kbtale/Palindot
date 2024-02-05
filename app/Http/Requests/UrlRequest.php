<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UrlRequest",
 *     type="object",
 *     description="Requires the data necessary to store and update a url",
 *     @OA\Property(
 *         property="base_url",
 *         type="string",
 *         description="The url that you want to shorten",
 *     ),
 *     @OA\Property(
 *         property="subset_id",
 *         type="string",
 *         description="The subset in which you want to store the url. It can be null and it's not required",
 *     )
 * )
 */
class UrlRequest extends FormRequest
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
            'base_url' => 'required',
            'subset_id' =>'sometimes|nullable'
        ];
    }
}
