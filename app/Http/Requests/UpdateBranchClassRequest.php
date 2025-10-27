<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchClassRequest extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branch_classes,code,' . $this->route('class'),
            'curriculum_type' => 'required|in:kurikulum_merdeka,kss,kssm,kbsr,kbsm,spm,stpm,modular',
            'grade_level' => 'required|in:year_1,year_2,year_3,year_4,year_5,year_6,form_1,form_2,form_3,form_4,form_5,form_6',
            'stream' => 'nullable|in:general,science,arts,commerce,technical,vocational,engineering,biology,accounting_finance,humanities_social',
            'academic_session' => 'required|string|max:50',
            'language_medium' => 'required|in:malay,english,chinese,tamil,bilingual,english_malay',
            'capacity' => 'required|integer|min:1|max:100',
            'advisor_id' => 'nullable|exists:staff,id',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Provide defaults for empty required enum fields
            'curriculum_type' => $this->input('curriculum_type') ?: 'kss',
            'grade_level' => $this->input('grade_level') ?: 'form_1',
            'language_medium' => $this->input('language_medium') ?: 'malay',
            'capacity' => $this->input('capacity') ?: 40,
            'status' => $this->has('status') ? (bool) $this->status : true,
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'curriculum_type.required' => 'Curriculum type is required',
            'grade_level.required' => 'Grade level / Tingkatan is required',
            'language_medium.required' => 'Language medium is required',
            'capacity.required' => 'Class capacity is required',
        ];
    }
}
