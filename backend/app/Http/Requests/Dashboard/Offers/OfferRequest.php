<?php

namespace App\Http\Requests\Dashboard\Offers;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'title' => 'required|string|max:255' ,
            'description' => 'nullable|string' ,
             'image' =>  'required|mimes:jpg,png,jpeg,gif,svg',
             'to' => 'required|date|after:from' ,
             'from' => 'required|date|before:to' ,
             'discount' => 'required|numeric' ,
             'menu_id' => 'required|numeric',
             'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
