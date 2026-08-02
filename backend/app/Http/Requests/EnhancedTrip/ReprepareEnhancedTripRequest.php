<?php

namespace App\Http\Requests\EnhancedTrip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReprepareEnhancedTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prepare_token' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'prepare_token.required' => __('dashboard.validation.prepare_token.required'),
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

