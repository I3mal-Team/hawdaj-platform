<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlaceRequest extends FormRequest
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
//        dd(request()->all());
//        $validatons = v_translations(["title", "description"]);
        return [
//            ...$validatons,
            'title' => 'required',
            'description' => 'required',
            'ceo_title' => 'nullable',
            'ceo_description' => 'nullable',
            'lat' => 'required_if:address_type,==,map',
            'long' => 'required_if:address_type,==,map',
            'categories' => ['required'],
            'image' => ['nullable'],
            'categories.*' => ['required', Rule::exists('categories', 'id')],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'price_id' => ['required', Rule::exists('prices', 'id')],
            'seasons' => ['required', 'array', Rule::in('spring', 'summer', 'winter', 'fall', 'all_year')],
            'address_type' => ['required', Rule::in('link', 'map', 'latlong')],
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
