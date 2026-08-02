<?php

namespace App\Http\Requests\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name' => ['required', 'string', 'min:3', 'max:50'],
            'phone' => ['nullable', 'numeric'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user ?? '')],
            'password' => $this->user ? "sometimes|nullable|confirmed|min:6" : "required|confirmed|min:4",
            'role' => ['required', 'numeric', Rule::exists('roles', 'id')],
            'department_id' => ['nullable', 'numeric', Rule::exists('departments', 'id')],
            'sites' => ['nullable', 'array', 'min:1'],
            'sites.*' => ['required', 'numeric', Rule::exists('sites', 'id')],
            'photo' => 'sometimes|nullable|' . v_image(),
            'region_id' => 'nullable|numeric|exists:regions,id',
        ];
    }
}
