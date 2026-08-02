<?php

namespace App\Http\Requests\EnhancedTrip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'prepare_token' => 'required|string|exists:enhanced_prepare_trips,token'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الرحلة مطلوب',
            'name.string' => 'اسم الرحلة يجب أن يكون نص',
            'name.max' => 'اسم الرحلة لا يمكن أن يتجاوز 255 حرف',
            'prepare_token.required' => 'رمز الرحلة المحضرة مطلوب',
            'prepare_token.string' => 'رمز الرحلة المحضرة يجب أن يكون نص',
            'prepare_token.exists' => 'رمز الرحلة المحضرة غير موجود'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()
        ], 422));
    }
}
