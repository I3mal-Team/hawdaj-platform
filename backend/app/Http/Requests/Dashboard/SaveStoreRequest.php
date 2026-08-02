<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required'],
            'categories' => ['required'],
            'region_id' => 'required_if:con_type,local|nullable|numeric|exists:regions,id',
            'city_id' => 'required_if:con_type,local|nullable|numeric|exists:cities,id',
            'categories.*' => ['required', Rule::exists('category_of_stores', 'id')],
            'con_type' => ['required'],
            // 'address_type' => 'required_if:con_type,==,local',
            // 'address' => 'required_if:con_type,==,online',
            'description' => ['required'],
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
