<?php

namespace App\Http\Requests\Places;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'search' => 'sometimes|string',
            'city_id' => 'sometimes|integer|exists:cities,id',
            'region_id' => 'sometimes|integer|exists:regions,id',
            'category_id' => 'sometimes',
            'sub_category_id' => 'sometimes',
            'lat' => 'sometimes',
            'lng' => 'sometimes',
            'x' => 'sometimes',
            'y' => 'sometimes',
            'price_id' => 'sometimes|exists:prices,id',
            'temperature' => 'sometimes'
        ];
    }
}
