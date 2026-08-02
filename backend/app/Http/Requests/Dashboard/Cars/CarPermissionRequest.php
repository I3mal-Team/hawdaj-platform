<?php

namespace App\Http\Requests\Dashboard\Cars;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarPermissionRequest extends FormRequest
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
            'site_id' => ['required_if:current_site,0', 'numeric', Rule::exists('sites', 'id')],
            'department_id' => ['required', 'numeric', Rule::exists('departments', 'id')],
            'host_id' => ['required', 'numeric', Rule::exists('users', 'id')],
            'id_number' => ['required'],
            'contact_person_name' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email'],
            'licence' => ['required'],
            'plate_ar' => ['required'],
            'plate_en' => ['required'],
            'delivery_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
            'delivery_from_time' => ['required', 'date_format:H:i'],
            'delivery_to_time' => ['required', 'date_format:H:i', 'after:delivery_from_time']
        ];
    }
    public function messages()
    {
        return [
            'id_number.required' => __('dashboard.carRequestValidation.id_number'),
            'contact_person_name.required' => __('dashboard.carRequestValidation.contact_person_name'),
            'phone.required' => __('dashboard.carRequestValidation.phone'),
            'email.required' => __('dashboard.carRequestValidation.email_required'),
            'email.email' => __('dashboard.carRequestValidation.email_email'),
            'licence.required' => __('dashboard.carRequestValidation.licence'),
            'plate_ar.required' => __('dashboard.carRequestValidation.plate_ar'),
            'plate_en.required' => __('dashboard.carRequestValidation.plate_en'),

            'site_id.required_if' => __('dashboard.carRequestValidation.site.required'),
            'site_id.numeric' => __('dashboard.carRequestValidation.site.numeric'),
            'site_id.exists' => __('dashboard.carRequestValidation.site.exists'),
            'department_id.required' => __('dashboard.carRequestValidation.department.required'),
            'department_id.numeric' => __('dashboard.carRequestValidation.department.numeric'),
            'department_id.exists' => __('dashboard.carRequestValidation.department.exists'),
            'host_id.required' => __('dashboard.carRequestValidation.host.required'),
            'host_id.numeric' => __('dashboard.carRequestValidation.host.numeric'),
            'host_id.exists' => __('dashboard.carRequestValidation.host.exists'),
            'delivery_date.required' => __('dashboard.carRequestValidation.delivery_date.required'),
            'delivery_date.date' => __('dashboard.carRequestValidation.delivery_date.date'),
            'delivery_date.after_or_equal' => __('dashboard.carRequestValidation.delivery_date.after_or_equal'),
            'delivery_from_time.required' => __('dashboard.carRequestValidation.delivery_from_time.required'),
            'delivery_from_time.date_format' => __('dashboard.carRequestValidation.delivery_from_time.date_format'),
            'delivery_to_time.required' => __('dashboard.carRequestValidation.delivery_to_time.required'),
            'delivery_to_time.date_format' => __('dashboard.carRequestValidation.delivery_to_time.date_format'),
            'delivery_to_time.after' => __('dashboard.carRequestValidation.delivery_to_time.after'),
        ];
    }
}
