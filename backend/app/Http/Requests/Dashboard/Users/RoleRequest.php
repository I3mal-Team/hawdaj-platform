<?php

namespace App\Http\Requests\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'name')
                    ->ignore($this->role ? $this->role->id : ""),
            ],
            'label' => ['required', 'string', 'max:50'],
            'permissions' => ['required'],
            'permissions.*' => ['required', 'min:1', 'exists:permissions,id'],
        ];
    }
}
