<?php

namespace App\Http\Requests;

use App\Models\SchoolClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other,prefer_not_to_say',
            'address' => 'required|string|max:500',
            'admission_number' => 'required|string|max:50|unique:students,admission_number',
            'class' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $class = SchoolClass::where('code', $value)
                        ->where('school_id', $this->school_id)
                        ->where('status', true)
                        ->first();
                    
                    if (!$class) {
                        $fail('The selected class is invalid for this school.');
                    }
                },
            ],
            'section' => 'required|string|max:10',
            'roll_number' => 'required|string|max:50',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'parent_email' => 'nullable|email|max:255',
            'medical_conditions' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'school_id.required' => 'Please select a school.',
            'school_id.exists' => 'The selected school does not exist.',
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name cannot exceed 255 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.regex' => 'Please enter a valid phone number.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Please enter a valid date.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Please select a valid gender option.',
            'address.required' => 'Address is required.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'admission_number.required' => 'Admission number is required.',
            'admission_number.unique' => 'This admission number is already in use.',
            'class.required' => 'Please select a class.',
            'section.required' => 'Section is required.',
            'roll_number.required' => 'Roll number is required.',
            'parent_name.required' => 'Parent/Guardian name is required.',
            'parent_phone.required' => 'Parent/Guardian phone number is required.',
            'parent_phone.regex' => 'Please enter a valid phone number.',
            'parent_email.email' => 'Please enter a valid email address.',
            'medical_conditions.max' => 'Medical conditions cannot exceed 1000 characters.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'school_id' => 'school',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'date_of_birth' => 'date of birth',
            'admission_number' => 'admission number',
            'parent_name' => 'parent/guardian name',
            'parent_phone' => 'parent/guardian phone',
            'parent_email' => 'parent/guardian email',
            'medical_conditions' => 'medical conditions',
        ];
    }
}
