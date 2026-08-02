<?php

namespace App\Http\Requests\Dashboard\Visits;

use Illuminate\Foundation\Http\FormRequest;

class VisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email'],
            'company' => ['nullable', 'string'],
            'position' => ['nullable', 'string'],
            'personal_photo' => 'nullable|' . v_image(),
            'id_copy' => 'nullable|' . v_image(),
        ];
    }
}
