<?php

namespace App\Http\Requests\Dashboard\Visits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitPermissionRequest extends FormRequest
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
            'description' => ['nullable'],
            'requirments' => ['required'],
            'from_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
            'from_fromtime' => ['required', 'date_format:H:i'],
            'from_totime' => ['required', 'date_format:H:i', 'after:from_fromtime'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'to_fromtime' => ['required', 'date_format:H:i'],
            'to_totime' => ['required', 'after:to_fromtime'],
        ];
    }

    public function messages()
    {
        return [
            'site_id.required' => __('dashboard.visitor_validation.site.required'),
            'site_id.numeric' => __('dashboard.visitor_validation.site.numeric'),
            'site_id.exists' => __('dashboard.visitor_validation.site.exists'),
            'department_id.required' => __('dashboard.visitor_validation.department.required'),
            'department_id.numeric' => __('dashboard.visitor_validation.department.numeric'),
            'department_id.exists' => __('dashboard.visitor_validation.department.exists'),
            'host_id.required' => __('dashboard.visitor_validation.host.required'),
            'host_id.numeric' => __('dashboard.visitor_validation.host.numeric'),
            'host_id.exists' => __('dashboard.visitor_validation.host.exists'),
            'reason_id.required' => __('dashboard.visitor_validation.reason_id'),
            'visit_type_id.required' => __('dashboard.visitor_validation.visit_type_id'),
            'requirments.required' => __('dashboard.visitor_validation.requirments'),
            'from_date.required' => __('dashboard.visitor_validation.from_date.required'),
            'from_date.date' => __('dashboard.visitor_validation.from_date.date'),
            'from_date.after_or_equal' => __('dashboard.visitor_validation.from_date.after_or_equal'),
            'from_fromtime.required' => __('dashboard.visitor_validation.from_fromtime.required'),
            'from_fromtime.date_format' => __('dashboard.visitor_validation.from_fromtime.date_format'),
            'from_totime.required' => __('dashboard.visitor_validation.from_totime.required'),
            'from_totime.date_format' => __('dashboard.visitor_validation.from_totime.date_format'),
            'from_totime.after' => __('dashboard.visitor_validation.from_totime.after'),
            'to_date.required' => __('dashboard.visitor_validation.to_date.required'),
            'to_date.date' => __('dashboard.visitor_validation.to_date.date'),
            'to_date.after_or_equal' => __('dashboard.visitor_validation.to_date.after_or_equal'),
            'to_fromtime.required' => __('dashboard.visitor_validation.to_fromtime.required'),
            'to_fromtime.date_format' => __('dashboard.visitor_validation.to_fromtime.date_format'),
            'to_totime.date_format' => __('dashboard.visitor_validation.to_totime.required'),
            'to_totime.after' => __('dashboard.visitor_validation.to_totime.after'),

        ];
    }
}
