<?php

namespace App\Http\Requests;

use App\Models\JobApplication;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobApplicationRequest extends FormRequest
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
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'job_title' => ['sometimes', 'required', 'string', 'max:255'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'application_date' => ['sometimes', 'required', 'date'],
            'application_status' => ['sometimes', 'required', Rule::in(JobApplication::STATUSES)],
            'job_source_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('job_sources', 'id')->where('user_id', $this->user()->id),
            ],
            'job_description' => ['nullable', 'string'],
            'job_url' => ['nullable', 'array'],
            'job_url.*' => ['string', 'url'],
        ];
    }
}
