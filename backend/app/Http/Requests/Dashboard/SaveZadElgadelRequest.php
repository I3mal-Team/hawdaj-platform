<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveZadElgadelRequest extends FormRequest
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
            'title' => ['required'],
            'description' => ['required'],
            'categories' => ['required'],
            'categories.*' => ['required', Rule::exists('category_of_zads', 'id')],
            'food_categories' => ['required'],
            'food_categories.*' => ['required', Rule::exists('food_category_of_zads', 'id')],
            'address_type' => ['required', Rule::in('link', 'map', 'latlong')],
            'address' => ['required_if:address_type,link'],
            'address_map' => ['nullable', 'string', 'max:2000'],
            'lat' => ['required_if:address_type,map', 'required_if:address_type,latlong', 'nullable', 'numeric'],
            'long' => ['required_if:address_type,map', 'required_if:address_type,latlong', 'nullable', 'numeric'],
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
