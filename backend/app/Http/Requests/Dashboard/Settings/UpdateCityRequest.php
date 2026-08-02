<?php

namespace App\Http\Requests\Dashboard\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:50'],
            'region_id' => 'nullable|exists:regions,id',
        ];
    }
}
