<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OnboardingSession;
use App\Models\Tenant;


class Step3Request extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'subdomain'=>[
                'required',
                'string',
                'regex:/^[a-z0-9-]+$/', // lowercase,numbers,dash
                'max:40',
                function ($attribute, $value, $fail) {
                    $normalized = strtolower(trim($value));
                     if (in_array($normalized, config('reserved.subdomains'))) {
                        $fail('This subdomain is reserved and cannot be used.');
                        return;
                    }
                    $token = session('onboarding_token');
                    $existsInSessions = OnboardingSession::where('subdomain', $value)
                    ->when($token, fn($q) => $q->where('token', '!=', $token))
                    ->exists();

                    $existsInTenants = Tenant::where('domain', $value)->exists();
                    if ($existsInSessions || $existsInTenants) {
                        $fail('The subdomain has already been taken.');
                    }
                }
            ],
        ];
    }
}
