<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class NotifcationRequest extends FormRequest
{
    /**
     * Determine if the dashboard is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'link' => 'sometimes|nullable|url',
            'app_type' => 'required',
            'send_type' => 'required',
            'send_to' => 'required',
        ];
    }

}
