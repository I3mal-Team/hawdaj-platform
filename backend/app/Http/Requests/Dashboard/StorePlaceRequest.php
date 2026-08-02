<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlaceRequest extends FormRequest
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
            'title' => ['required', 'unique:place_translations,title'],
            'categories' => ['required'],
            'description' => ['required'],
            'image' => ['required'],
            'categories.*' => ['required', Rule::exists('categories', 'id')],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'price_id' => ['required', Rule::exists('prices', 'id')],
            'seasons' => ['required', 'array', Rule::in('spring', 'summer', 'winter', 'fall' , 'all_year')],
            'address_type' => ['required', Rule::in('link', 'map', 'latlong')],
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
