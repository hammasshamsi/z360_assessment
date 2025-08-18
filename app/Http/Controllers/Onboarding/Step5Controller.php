<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnboardingSession;
use App\Http\Requests\Step5Request;
use App\Services\TenantProvisioningService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\URL;

class Step5Controller extends Controller
{
    public function show(Request $request){
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid signature.');
        }

        $token = $request->query('token') ?? session('onboarding_token');

        if (! $token) {
            return redirect()->route('onboarding.step1');
        }

        session(['onboarding_token' => $token]);

        $session = OnboardingSession::where('token', $token)->firstOrFail();

        // return view('onboarding.step5', compact('session'));
        return Inertia::render('Onboarding/Step5', [
            'summary' => $session->toArray(),
            'editUrls' => [
                'step1' => URL::signedRoute('onboarding.step1', ['token' => $session->token]),
                'step3' => URL::signedRoute('onboarding.step3', ['token' => $session->token]),
                'step4' => URL::signedRoute('onboarding.step4', ['token' => $session->token]),
            ]
        ]);
    }

    public function store(Step5Request $request, TenantProvisioningService $service)
    {
        $token = session('onboarding_token');
        $session = OnboardingSession::where('token', $token)->firstOrFail();
        
        $tenant = $service->provision($session);

        // clear onboarding session as it's complete
        session()->forget('onboarding_token');
        // return redirect()->route('tenant.login', ['subdomain' => $tenant->domain]);

        return response()->json([
            'message' => 'Provisioning started successfully.',
            'subdomain' => $tenant->domain,
        ]);
    }
}
