<?php

namespace App\Http\Requests\Dashboard\Visits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitActivityRequest extends FormRequest
{
    /**
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
            'site_id' => ['required_if:current_site,0', 'numeric', Rule::exists('sites', 'id')],
            'department_id' => ['required', 'numeric', Rule::exists('departments', 'id')],
            'host_id' => ['required', 'numeric', Rule::exists('users', 'id')],
            'reason_id' => ['required'],
            'visit_type_id' => ['required'],
        ];
    }
}
