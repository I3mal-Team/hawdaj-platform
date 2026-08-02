<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'username' => 'Username',
        'email' => 'email',
        'first_name' => 'first name',
        'last_name' => 'last name',
        'password' => 'password',
        'password_confirmation' => 'Confirm password',
        'city' => 'City',
        'country' => 'Country',
        'address' => 'address',
        'phone' => 'phone',
        'mobile' => 'mobile',
        'age' => 'age',
        'sex' => 'sex',
        'gender' => 'Type',
        'day' => 'day',
        'month' => 'month',
        'year' => 'Year',
        'hour' => 'hour',
        'minute' => 'minute',
        'second' => 'second',
        'title' => 'title',
        'content' => 'content',
        'excerpt' => 'abstract',
        'date' => 'date',
        'time' => 'time',
        'available' => 'available',
        'size' => 'size',
        'type' => 'type',
        'role_id' => 'The role field is required',
        'permissions' => 'Permissions',
        'user_id' => 'dashboard',
        'user_status' => 'dashboard type',
        'old' => 'select all users',
        'percentage' => 'employee percentage',
        'amount_per_case' => 'Employee percentage per case',
        'percentage_patient_from_doctor' => 'percentage field per patient',
        'product_id' => 'product name',
        'supplier_id' => 'supplier name',
        'price' => 'price',
        'cost_per_item' => 'Unit Price',
        'quantity' => 'quantity',
        'amount_paid' => 'amount paid',
        'due_date' => 'due date',
        'name' => 'name',
        'measurement' => 'measurement',
        'update_check' => 'Date',
        'expense_name' => 'expense name',
        'investigation_name' => 'investigation name',
        'perception.*.name' => 'name',
        'company_name' => 'company name',
        'company_type' => 'company type',
        'discount_percentage' => 'discount percentage',
        'date_from' => 'date from',
        'date_to' => 'date to',
        'recurring_date' => 'recurring date',
        'recurring_day' => 'recurring day',
        'total_discount' => 'total discount',
        'total_price' => 'total_price',
        'reservation_price' => 'reservation price',
        'reservation_discount' => 'reservation discount',
        'patient_name' => 'patient name',
        'patient_mobile' => 'patient mobile',
        'salary_nurse' => 'salary nurse',
        'salary_user' => 'salary user',
        'salary_assistant' => 'salary assistant',
        'doctor_type' => 'doctor type',
        'case_from_doctor' => 'case from doctor',
        'case_from_organization' => 'case from organization',
        'reservation_amount' => 'reservation amount',
        'following_up_amount' => 'following up amount',
        'site_id' => 'Site',
        'department_id' =>  'Department',
        'host_id' =>  'Host',
        'reason_id' =>  'Reason',
        'visit_type_id' =>  'Visit Type',
        'description' =>  'Description',
        'requirments' =>  'Requirements',
        'from_date' =>  'From Date',
        'from_fromtime' =>  'From',
        'from_totime' =>  'To',
        'to_date' =>  'To Date',
        'to_fromtime' =>  'From',
        'to_totime' =>  'To',
    ],

];
