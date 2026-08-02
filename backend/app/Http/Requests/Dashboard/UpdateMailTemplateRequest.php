<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMailTemplateRequest extends FormRequest
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
//            'title' => 'required',
//            'subject' => 'required',
            'content' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('dashboard.mail_templates_error.title_required'),
            'subject.required' => __('dashboard.mail_templates_error.subject_required'),
            'subject.unique' => __('dashboard.mail_templates_error.subject_unique'),
            'content.required' => __('dashboard.mail_templates_error.content_required'),
        ];
    }
}
