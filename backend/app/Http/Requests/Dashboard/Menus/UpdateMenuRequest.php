<?php

namespace App\Http\Requests\Dashboard\Menus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'en.title' => 'required|string|max:255',
            'ru.title' => 'required|string|max:255',
            'zh.title' => 'required|string|max:255',
            'ar.description' => 'required|string',
            'en.description' => 'required|string',
            'ru.description' => 'required|string',
            'zh.description' => 'required|string',
            'price' => 'required|numeric',
            'zad_id' => 'required|numeric|exists:zad_elgadels,id',
            'image' => 'nullable|mimes:jpg,png,jpeg,gif,svg',
            'order_id' => ['nullable', 'integer', 'min:0'],

        ];
    }
}
