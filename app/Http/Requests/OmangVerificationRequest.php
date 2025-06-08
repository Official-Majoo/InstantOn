<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OmangVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Since we're already using middleware for authentication,
        // we can return true here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'omang_number' => 'required|string|size:9|regex:/^\d{9}$/',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'omang_number.required' => 'The Omang number is required.',
            'omang_number.size' => 'The Omang number must be exactly 9 digits.',
            'omang_number.regex' => 'The Omang number must contain only digits.',
        ];
    }
}