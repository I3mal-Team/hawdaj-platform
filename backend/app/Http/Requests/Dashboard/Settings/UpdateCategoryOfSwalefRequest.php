<?php

namespace App\Http\Requests\Dashboard\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryOfSwalefRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'notes' => 'nullable',
            'icon' => 'sometimes|nullable|' . v_image(),
            'parent_id' => 'nullable|exists:category_of_applications,id',
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
