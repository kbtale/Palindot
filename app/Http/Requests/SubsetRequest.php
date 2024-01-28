<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @SWG\Definition(
 *     definition="SubsetRequest",
 *     type="object",
 *     description="Requires the data necessary to store and update a subset",
 *     @SWG\Property(
 *         property="subset_name",
 *         type="string",
 *         description="The name of the subset",
 *     ),
 *     @SWG\Property(
 *         property="subset_descr",
 *         type="string",
 *         description="The description of the subset. It can be null and it's not required. Must contain 20 characters at least",
 *     )
 * )
 */
class SubsetRequest extends FormRequest
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
            'subset_name' => 'required|max:225',
            'subset_descr' => 'sometimes|nullable|min:20|max:225'
        ];
    }
}
