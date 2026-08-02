<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCeoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [request('type') == 'places' ? 'nullable' : 'required', 'string', 'max:255'],
            'description'=>[request('type') == 'places' ? 'nullable' : 'required', 'string'],
            'ceo_title' => [request('type') == 'places' ? 'required' : 'nullable', 'string', 'max:255'],
            'ceo_description'=>[request('type') == 'places' ? 'required' : 'nullable', 'string'],
            'link' => 'required|url',
            'key_words' => 'required|array',
            'parent_id' => ['required', 'numeric'],
        ];
    }
}
