<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\URL;

class ResumeController extends Controller
{

    public function __invoke(Request $request)
    {
        // check if its coming from resume or direct link so handle accordingly
        // if token is present in the request, it means user clicked on a link to resume
        // otherwise it means user trying to resume directly from the session stored in session
        if ($request->has('token')) {
            return $this->handleLinkResume($request);
        }

        return $this->handleDirectResume();
    }

    protected function handleDirectResume()
    {
        $token = session('onboarding_token');

        if (!$token) {
            return redirect()->route('onboarding.step1');
        }

        $session = OnboardingSession::where('token', $token)->first();

        if (!$session) {
            return redirect()->route('onboarding.step1');
        }

        return $this->redirectToStep($session);
    }

    protected function handleLinkResume(Request $request)
    {
        // clear any existing session so not conflicts occurs
        session()->forget('onboarding_token');
        
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'No session token provided.');
        }

        $session = OnboardingSession::where('token', $token)
            ->where('is_complete', false)
            ->first();
        
        if (!$session) {
            return redirect()->route('onboarding.step1')
                ->with('error', 'Invalid or expired session.');
        }

        // set this as new session token
        session(['onboarding_token' => $token]);

        return $this->redirectToStep($session);
    }

    protected function redirectToStep($session)
    {
        if (!$session->full_name || !$session->email) {
            return redirect()->signedRoute('onboarding.step1', ['token' => $session->token]);
        }

        if (!$session->password) {
            return redirect()->signedRoute('onboarding.step2', ['token' => $session->token]);
        }

        if (!$session->company_name || !$session->subdomain) {
            return redirect()->signedRoute('onboarding.step3', ['token' => $session->token]);
        }

        if (!$session->billing_name || !$session->billing_address || !$session->country) {
            return redirect()->signedRoute('onboarding.step4', ['token' => $session->token]);
        }

        return redirect()->signedRoute('onboarding.step5', ['token' => $session->token]);
    }
}
