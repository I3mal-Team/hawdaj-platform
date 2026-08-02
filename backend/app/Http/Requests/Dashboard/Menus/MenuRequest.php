<?php

namespace App\Http\Requests\Dashboard\Menus;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'zad_id' => 'required|numeric|exists:zad_elgadels,id',
            'image' => 'required|mimes:jpg,png,jpeg,gif,svg',
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
