<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\OnboardingSession;
use App\Models\Tenant;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class Step5Request extends FormRequest
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
            //
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $token = session('onboarding_token');

            if (! $token) {
                throw ValidationException::withMessages(['session' => 'onboarding session not found.']);
            }

            $session = OnboardingSession::where('token', $token)->first();

            if (! $session) {
                throw ValidationException::withMessages(['session' => 'onboarding session expired or invalid.']);
            }

            // finally check all required onboarding fields are filled
            //  we can also make function of this step but this is still good because we follow DRY
            // and this is the last step so we can do it here
            $requiredFields = ['full_name', 'email', 'password', 'company_name', 'subdomain', 'billing_name', 'billing_address', 'country', 'phone'];
            foreach ($requiredFields as $field) {
                if (empty($session->{$field})) {
                    $validator->errors()->add($field, ucfirst(str_replace('_', ' ', $field)) . ' is missing.');
                }
            }

            // check duplicate subdomain -- email in tenants
            // we can also make function of this step but this is still good because we follow DRY
            if (Tenant::where('domain', $session->subdomain)->exists()) {
                $validator->errors()->add('subdomain', 'This subdomain is already taken.');
                $url = URL::signedRoute('onboarding.step4', ['token' => $session->token]);
                return redirect($url);
            }

            if (Tenant::where('email', $session->email)->exists()) {
                $validator->errors()->add('email', 'This email is already associated with another tenant.');
                $url = URL::signedRoute('onboarding.step1', ['token' => $session->token]);
                return redirect($url);
            }
        });
    }
}
