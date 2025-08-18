<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Step3Request;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;  
use Inertia\Response;


class Step3Controller extends Controller
{
    public function show(Request $request){
        
        if(! $request->hasValidSignature()) {
             abort(403, 'Invalid or Expired Link.');
        }
        // retoring session
        $token = $request->query('token');
        if (! $token) {
            return redirect()->route('onboarding.step1')
                ->withErrors('Your onboarding session has expired. Please start again.');
        }
        session(['onboarding_token' => $token]);
        $session = OnboardingSession::where('token', $token)->firstorFail();
        // return view('onboarding.step3', compact('session'));
        return Inertia::render('Onboarding/Step3', [
            'sessionData' => $session->only('company_name', 'subdomain')
        ]);
    }

    public function store(Step3Request $request)
    {
        $validated = $request->validated();
        $token = session('onboarding_token');
        
        if (!$token) {
            return redirect()->route('onboarding.step1');
        }
        $session = OnboardingSession::where('token', $token)->firstOrFail();
        $session->update([
            'company_name' => $validated['company_name'],
            'subdomain' => strtolower($validated['subdomain']),
        ]);
        $url = URL::signedRoute('onboarding.step4', ['token' => $session->token]);
        return redirect($url);
        // return redirect()->route('onboarding.step4', ['token' => $session->token]);
    }
}
