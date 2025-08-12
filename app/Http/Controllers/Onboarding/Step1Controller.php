<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\Request;
use App\Http\Requests\Step1Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\URL;

class Step1Controller extends Controller
{
    /**
     * Show the first step of the onboarding process.
     */
    public function show()
    {
        $token = session('onboarding_token');

        $session = null;
        if ($token) {
            $session = OnboardingSession::where('token', $token)->first();
        }
        return view('onboarding.step1', compact('session'));
    }

    /**
     * Store the data from the first step of the onboarding process.
     */
    public function store(Step1Request $request)
    {
        $validated = $request->validated();
        $token = session('onboarding_token');

        $session = $token ? 
            OnboardingSession::where('token', $token)->first()
            : null;
        if ($session){
            $session->update($validated);
        } else{
            $token = (string) Str::uuid();

            $session = OnboardingSession::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'token' => $token,
            ]);
            
            session(['onboarding_token' => $token]);
        }
        //generating signed continuation url with toke
        $url = URL::signedRoute('onboarding.step2',['token'=>$token]);
        return redirect($url);
    }
}
