<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInboxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Public contact form - always authorized.
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
            'name' => 'required|string|max:255|regex:/^[\pL\s\-\']+$/u',
            'email' => 'required|email:rfc,dns,spoof,filter|max:255',
            'subject' => 'required|string|max:255|regex:/^[\pL\s\-\'\.\,\?\!]+$/u',
            'message' => 'required|string|max:5000',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'The name field may only contain letters, spaces, hyphens, and apostrophes.',
            'email.email' => 'Please provide a valid email address.',
            'subject.regex' => 'The subject field contains invalid characters.',
        ];
    }
}
