<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacialVerificationRequest extends FormRequest
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
            'selfie' => 'nullable|file|image|max:5120', // 5MB max
            'selfie_base64' => 'nullable|string',
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
            'selfie.image' => 'The selfie must be an image file.',
            'selfie.max' => 'The selfie file size may not be greater than 5MB.',
            'selfie_base64.string' => 'The base64 image data is invalid.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Ensure at least one of selfie or selfie_base64 is provided
            if (!$this->hasFile('selfie') && !$this->filled('selfie_base64')) {
                $validator->errors()->add('selfie', 'A selfie image is required.');
            }
        });
    }
}