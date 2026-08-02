<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'description' => 'required|min:5|string',
            'type' => 'required|string|in:link,map,latlng',
            'lat' => [$this->type !== 'link'?'required':'nullable','numeric'],
            'long' => [$this->type !== 'link'?'required':'nullable','numeric'],
            'address' => [$this->type == 'link' || $this->type == 'url' ? 'required' : 'nullable'],
            'display_type' => 'required|string|in:banner,top_bar',
            'facebook' => 'nullable|url',
            'whatsapp' => 'nullable|numeric',
            'instagram' => 'nullable|url',
            'website' => 'nullable|url',
            'ticket_link' => 'nullable|url',
            'video_url' => 'nullable|url',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
            'visited' => 'required|numeric|in:1,0',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp,png',
            'region_id' => 'required|numeric|exists:regions,id',
            'city_id' => 'required|numeric|exists:cities,id',
            'active' => 'nullable|string|max:255',
            'featured' => 'nullable|string|max:255',
            'order_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
