<?php

namespace App\Http\Requests\EnhancedPrepareTrip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PrepareTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_region_id' => 'required|exists:regions,id',
            'end_region_id' => 'required|exists:regions,id',
            'places_per_day' => 'required|integer|min:1|max:20',
            'categories' => 'nullable|array',
            'categories.*' => 'integer',
            'price_range' => 'nullable|array',
            'price_range.*' => 'integer'
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => __('dashboard.validation.start_date.required'),
            'start_date.date' => __('dashboard.validation.start_date.date'),
            'start_date.after_or_equal' => __('dashboard.validation.start_date.after_or_equal'),
            'end_date.required' => __('dashboard.validation.end_date.required'),
            'end_date.date' => __('dashboard.validation.end_date.date'),
            'end_date.after_or_equal' => __('dashboard.validation.end_date.after_or_equal'),
            'start_region_id.required' => __('dashboard.validation.start_region_id.required'),
            'start_region_id.exists' => __('dashboard.validation.start_region_id.exists'),
            'end_region_id.required' => __('dashboard.validation.end_region_id.required'),
            'end_region_id.exists' => __('dashboard.validation.end_region_id.exists'),
            'places_per_day.required' => __('dashboard.validation.places_per_day.required'),
            'places_per_day.integer' => __('dashboard.validation.places_per_day.integer'),
            'places_per_day.min' => __('dashboard.validation.places_per_day.min'),
            'places_per_day.max' => __('dashboard.validation.places_per_day.max'),
            'categories.array' => __('dashboard.validation.categories.array'),
            'categories.*.integer' => __('dashboard.validation.categories.integer'),
            'price_range.array' => __('dashboard.validation.price_range.array'),
            'price_range.*.integer' => __('dashboard.validation.price_range.integer'),
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

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth('api')->id()
        ]);
    }
}
