<?php

namespace App\Http\Requests\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'model' => ['required', 'string', 'max:50', Rule::unique('permissions','model')->ignore($this->model ,'model')],
            'operations' => ['required', 'array'],
            'operations.*' => ['required', 'min:1'],
        ];
    }
}
