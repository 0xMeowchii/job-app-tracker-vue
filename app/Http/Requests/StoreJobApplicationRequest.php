<?php

namespace App\Http\Requests;

use App\Models\JobApplication;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobApplicationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
          return [
            'company_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'application_date' => ['required', 'date'],
            'application_status' => ['required', Rule::in(JobApplication::STATUSES)],
            'source' => ['required', Rule::in(JobApplication::SOURCES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
