<?php

namespace App\Http\Requests\Dashboard\Applications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
             'image' => ['required', 'mimes:jpg,png,jpeg,gif,svg'],
             'type' => ['required', 'string', 'in:app,web'],
             'link' => ['nullable', 'string'],
             'ios_link' => ['nullable', 'string'],
             'android_link' => ['nullable', 'string'],
            'categories' => ['required'],
            'categories.*' => ['required', Rule::exists('category_of_applications', 'id')],
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
