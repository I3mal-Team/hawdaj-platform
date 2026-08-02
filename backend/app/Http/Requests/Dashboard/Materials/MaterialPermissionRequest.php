<?php

namespace App\Http\Requests\Dashboard\Materials;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialPermissionRequest extends FormRequest
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
            'type' => ['required'],
            'site_id' => ['required_if:current_site,0', 'numeric', Rule::exists('sites', 'id')],
            'department_id' => ['required', 'numeric', Rule::exists('departments', 'id')],
            'host_id' => ['required', 'numeric', Rule::exists('users', 'id')],
            'company' => ['nullable'],
            'contact_person' => ['nullable'],
            'phone' => ['nullable'],
            'email' => ['nullable', 'email'],

            'contact_company' => ['nullable'],
            'contact_person_name' => ['nullable'],
            'contact_person_email' => ['nullable'],
            'delivery_date' => ['nullable', 'date', 'after_or_equal:' . date('Y-m-d')],
            "delivery_from_time" => ['nullable', 'date_format:H:i'],
            "delivery_to_time" => ['nullable', 'date_format:H:i', 'after:delivery_from_time'],

            'dispatch_date' => ['nullable', 'date', 'after_or_equal:' . date('Y-m-d')],
            "dispatch_from_time" => ['nullable', 'date_format:H:i'],
            "dispatch_to_time" => ['nullable', 'date_format:H:i', 'after:dispatch_from_time'],

            'return_date' => ['nullable', 'date', 'after_or_equal:' . date('Y-m-d')],
            "return_from_time" => ['nullable', 'date_format:H:i'],
            "return_to_time" => ['nullable', 'date_format:H:i', 'after:return_from_time'],

        ];
    }

    public function messages(): array
    {
        return [
            "email.email" => __('dashboard.material_validation.email'),
            "contact_person_email.required" => __('dashboard.material_validation.contact_person_email_required'),
            "email.contact_company.required" => __('dashboard.material_validation.contact_company_required'),
            "type.required" => __('dashboard.material_validation.type_required'),
            "company.required" => __('dashboard.material_validation.company_required'),
            "contact_person.required" => __('dashboard.material_validation.contact_person_required'),
            "phone.required" => __('dashboard.material_validation.phone_required'),
            "delivery_date.date" => __('dashboard.material_validation.delivery_date.date'),
            "delivery_date.after_or_equal" => __('dashboard.material_validation.delivery_date.after_or_equal'),
            "delivery_from_time.date_format" => __('dashboard.material_validation.delivery_date.from_format_time'),
            "delivery_to_time.date_format" => __('dashboard.material_validation.delivery_date.to_format_time'),
            "delivery_to_time.after" => __('dashboard.material_validation.delivery_date.after'),
            "dispatch_date.date" => __('dashboard.material_validation.dispatch_date.date'),
            "dispatch_date.after_or_equal" => __('dashboard.material_validation.dispatch_date.after_or_equal'),
            "dispatch_date.date_format" => __('dashboard.material_validation.dispatch_date.from_format_time'),
            "dispatch_date.after" => __('dashboard.material_validation.dispatch_date.after'),
            "return_date.date" => __('dashboard.material_validation.return_date.date'),
            "return_date.after_or_equal" => __('dashboard.material_validation.return_date.after_or_equal'),
            "return_date.after" => __('dashboard.material_validation.return_date.after'),
            'site_id.required' => __('dashboard.material_validation.site.required'),
            'site_id.numeric' => __('dashboard.material_validation.site.numeric'),
            'site_id.exists' => __('dashboard.material_validation.site.exists'),
            'department_id.required' => __('dashboard.material_validation.department.required'),
            'department_id.numeric' => __('dashboard.material_validation.department.numeric'),
            'department_id.exists' => __('dashboard.material_validation.department.exists'),
            'host_id.required' => __('dashboard.material_validation.host.required'),
            'host_id.numeric' => __('dashboard.material_validation.host.numeric'),
            'host_id.exists' => __('dashboard.material_validation.host.exists'),
        ];
    }
}
