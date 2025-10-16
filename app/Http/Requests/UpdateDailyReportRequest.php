<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class UpdateDailyReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::authorize('update', $this->route('daily'))->allowed();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:users,id', 'exists:model_has_roles,model_id,role_id,' . Role::findByName('student')->id],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'report' => ['required', 'string', 'min:10'],
            'status' => ['required', 'string', 'in:draft,published'],
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'student_id.exists' => 'The selected student is not valid.',
            'date.before_or_equal' => 'The date must be today or in the past.',
        ];
    }
}
