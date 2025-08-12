<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OnboardingSession;
use Illuminate\Validation\Rule;
use App\Models\Tenant;

class Step1Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // so for pucblic onboarding no need of authorize
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'full_name' => 'required|string|max:255',
           'email' => [
                'required',
                'email',
                'max:255',
                function($attribute, $value, $fail) {
                    $currentToken = session('onboarding_token');
                    // checkin onboarding session -- ignore current session if resuming
                    $existingSession = OnboardingSession::where('email', $value)
                        ->when($currentToken, fn($q) => $q->where('token', '!=', $currentToken))
                        ->exists();
                    $existing_tenants = Tenant::where('email', $value)->exists();
                    if ($existingSession || $existing_tenants) {
                        $fail('The email has already been taken.');
                    }
                },

            ],
           
        ];
    }
}
