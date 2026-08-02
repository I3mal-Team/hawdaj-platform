<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'nullable',
            'con_type' => 'required|in:online,local',
            'lat' => 'nullable',
            'long' => 'nullable',
            'address' => 'required_if:address_type,==,link',
            'categories' => ['required', 'array'],
            'categories.*' => ['required', Rule::exists('category_of_stores', 'id')],
            'whatsapp' => ['nullable'],
            // 'facebook_link' => ['nullable'],
            'x_link' => ['nullable'],
            'region_id' => 'required_if:con_type,local|nullable|numeric|exists:regions,id',
            'city_id' => 'required_if:con_type,local|nullable|numeric|exists:cities,id',
            'order_id' => ['nullable', 'integer', 'min:0'],

        ];
    }
}
