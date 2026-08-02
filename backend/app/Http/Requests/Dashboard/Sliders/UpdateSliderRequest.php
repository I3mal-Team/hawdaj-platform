<?php

namespace App\Http\Requests\Dashboard\Sliders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg,webp', 'max:5120'],
            'order_id' => ['nullable', 'integer', 'min:0'],
            'link' => ['nullable', 'string', 'max:2048'],
            'active' => ['nullable', 'boolean'],
        ];
    }
}
