<?php

namespace App\Http\Requests\Dashboard\Settings;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'position' => ['nullable', 'string'],
            'mobile' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'url' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
