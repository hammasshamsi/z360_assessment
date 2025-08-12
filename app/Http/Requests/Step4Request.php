<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OnboardingSession;

class Step4Request extends FormRequest
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
            'billing_name'=> ['required', 'string', 'max:255'],
            'billing_address'=> ['required', 'string', 'max:1000'],
            'country' =>[
                'required',
                'string',
                'max:3',
                function ($attribute, $value, $fail) {
                    $allowed = config('countries.allowed',[]);
                    if (! empty($allowed) && !in_array(strtoupper($value), $allowed)) {
                        $fail('Selected country is invalid or Supported.');
                    }
                },
            ],
            'phone'=>[
                'required',
                'string',
                'max:20',
                'regex:/^\+[1-9]\d{1,14}$/', // Basic phone number validation
                function ($attr, $value, $fail) {
                    $token = session('onboarding_token');
                    if ($token) {
                        // aadditional condition check within onboarding_sessions
                        $exists = OnboardingSession::where('phone', $value)
                            ->where('token', '!=', $token)
                            ->exists();
                        if ($exists) {
                            $fail('This phone number is already associated with another onboarding.');
                        }
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number must be in E.164 format (e.g. +923001234567)',
        ];
    }
}
