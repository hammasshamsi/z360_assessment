<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Step2Request extends FormRequest
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
            'password'=> [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/', // atleast one lowercase letter
                'regex:/[A-Z]/', // atleast one uppercase letter
                'regex:/[0-9]/', // atleast one digit
                'regex:/[@$!%*?&]/', // atleast one special character
                'confirmed', //matches password_confirm
            ],
        ];
    }

    public function messages():array
    {
        return [
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
