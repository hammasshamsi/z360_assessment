<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Step4Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Inertia\Response;


class Step4Controller extends Controller
{
    public function show(Request $request){
        if(! $request->hasValidSignature()) {
             abort(403, 'Invalid or Expired Link.');
        }
        // retoring session
        $token = $request->query('token') ?? session('onboarding_token');
        if (! $token) {
            return redirect()->route('onboarding.step1')
                ->withErrors('Your onboarding session has expired. Please start again.');
        }
        session(['onboarding_token' => $token]);
        $session = OnboardingSession::where('token', $token)->firstOrFail();

        // get allowec countries directly from config
        $allowedCountries = Config::get('countries.allowed', []);

        // return view('onboarding.step4', compact('session'));
        return Inertia::render('Onboarding/Step4', [
            'sessionData' => $session->only('billing_name', 'billing_address', 'country', 'phone'),
            'allowedCountries' => $allowedCountries,
        ]);
    }

    public function store(Step4Request $request)
    {
        $validated = $request->validated();
        $token = session('onboarding_token');
        
        if (!$token) {
            return redirect()->route('onboarding.step1')
                ->withErrors('Your onboarding session has expired. Please start again.');
        }
        $session = OnboardingSession::where('token', $token)->firstOrFail();
        $session->update([
            'billing_name' => $validated['billing_name'],
            'billing_address' => $validated['billing_address'],
            'country' => strtoupper($validated['country']),
            'phone' => $validated['phone'],
        ]);
        $signed = URL::signedRoute('onboarding.step5', ['token' => $token]);

        return redirect($signed);
    }
}
