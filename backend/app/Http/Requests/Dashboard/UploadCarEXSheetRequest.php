<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UploadCarEXSheetRequest extends FormRequest
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
            'car_excel' => 'required|mimes:xlsx,csv'
        ];
    }

    public function messages()
    {
        return [
            'car_excel.required' => __('dashboard.fileRequired'),
            'car_excel.mimes' => __('dashboard.fileWrongExertions')
        ];
    }
}
