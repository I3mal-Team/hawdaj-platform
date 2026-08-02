<?php

namespace App\Http\Requests\Dashboard\Settings;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the dashboard is authorized to make this request.
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
            'name'      => ['required', 'string', 'max:50'],
            'notes'     => ['nullable', 'string'],
            'icon'      => 'sometimes|mimes:jpeg,jpg,png,svg|nullable|' . v_image(),
            'parent_id' => 'nullable|exists:categories,id',
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
