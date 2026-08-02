<?php

namespace App\Http\Requests\Dashboard\Offers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'title' => 'required|string|max:255' ,
            'description' => 'nullable|string' ,
            'to' => 'required|date|after:from' ,
            'from' => 'required|date|before:to' ,
            'discount' => 'required|numeric' ,
            'menu_id' => 'required|numeric',
            'image' => 'nullable|mimes:jpg,png,jpeg,gif,svg',
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
