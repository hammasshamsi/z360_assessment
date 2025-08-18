<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OnboardingSession;

class EnsureOnboardingProgression
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //enforce strict progression on onboarding
        $routeName = $request->route()->getName();

        // because step1 is entry points, we allow to pass without token and checks
        // we aadded resume because is designed to resume if they abandoned it, its always redirect to step1 if resume dont have token
        if (in_array($routeName, ['onboarding.step1', 'onboarding.resume'])) {
            return $next($request);
        }

        $token = $request->query('token') ?? session('onboarding_token');
        $session = OnboardingSession::where('token', $token)->first();
        if (!$session) {
            return redirect()->route('onboarding.step1')
                ->withErrors('Invalid or expired session. Please start onboarding from the beginning.');
        }
        if ($session->is_complete) {
            return redirect()->route('onboarding.success', ['tenant' => $session->tenant_id])
                ->with('success', 'Onboarding already completed.');
        }

        //requirements for each step
        $requirements = [
            'onboarding.step2' => ['full_name', 'email'],
            'onboarding.step3' => ['full_name', 'email', 'password'],
            'onboarding.step4' => ['full_name', 'email', 'password', 'company_name', 'subdomain'],
            'onboarding.step5' => [
                'full_name', 'email', 'password', 'company_name', 'subdomain',
                'billing_name', 'billing_address', 'billing_country', 'billing_phone'
            ],
        ];
        if (isset($requirements[$routeName])) {
            foreach ($requirements[$routeName] as $field) {
                if (empty($session->$field)) {
                    $stepRedirect = $this->getRedirectStep($field);
                    return redirect()->route($stepRedirect)
                        ->withErrors('Please complete previous steps before continuing.');
                }
            }
        }

        return $next($request);
    }

    protected function getRedirectStep($field){
        $map= [
            'full_name' => 'onboarding.step1',
            'email' => 'onboarding.step1',
            'password' => 'onboarding.step2',
            'company_name' => 'onboarding.step3',
            'subdomain' => 'onboarding.step3',
            'billing_name' => 'onboarding.step4',
            'billing_address' => 'onboarding.step4',
            'billing_country' => 'onboarding.step4',
            'billing_phone' => 'onboarding.step4',
        ];
        return $map[$field] ?? 'onboarding.step1'; // default is step1
        
    }

}
