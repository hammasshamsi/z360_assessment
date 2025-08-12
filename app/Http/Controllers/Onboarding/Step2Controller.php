<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Step2Request;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;


class Step2Controller extends Controller
{
    public function show(Request $request)
    {
        if(!$request->hasValidSignature()){
            abort(403, 'Invalid or Expired Link.');
        }
        $token = $request->query('token') ?? session('onboarding_token');
        if (! $token) {
            return redirect()->route('onboarding.step1')
                ->withErrors('Your onboarding session has expired. Please start again.');
        }

        session(['onboarding_token' => $token]);
        $session = OnboardingSession::where('token', $token)->firstorFail();
        
        return view('onboarding.step2', compact('session'));
    }

    public function store(Step2Request $request)
    {
        $validated = $request->validated();
        $token = session('onboarding_token');
        if(!$token){
            abort(403, 'Invalid or Expired Session./nToken not found in session.');
        }
        $session = OnboardingSession::where('token', $token)->firstOrFail();
        $session->update([
            'password' => Hash::make($validated['password']),
        ]);
        $url = URL::signedRoute('onboarding.step3', ['token' => $session->token]);
        return redirect($url);
    }
}
