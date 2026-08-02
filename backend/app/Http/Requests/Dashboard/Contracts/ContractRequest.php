<?php

namespace App\Http\Requests\Dashboard\Contracts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContractRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'company_id' => ['required', 'numeric', Rule::exists('companies', 'id')],
            'department_id' => ['required', 'numeric', Rule::exists('departments', 'id')],
            'supervisor_id' => ['required', 'numeric', Rule::exists('users', 'id')],
            'contract_manager_id' => ['required', 'numeric', Rule::exists('users', 'id')],
            'contract_type_id' => ['required', 'numeric', Rule::exists('contract_types', 'id')],
        ];
    }
}
