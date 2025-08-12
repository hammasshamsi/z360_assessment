<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnboardingSession;
use App\Http\Requests\Step5Request;
use App\Services\TenantProvisioningService;

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

        return view('onboarding.step5', compact('session'));
    }

    public function store(Step5Request $request, TenantProvisioningService $service)
    {
        $token = session('onboarding_token');
        $session = OnboardingSession::where('token', $token)->firstOrFail();

        $tenant = $service->provision($session);

        // clear onboarding session as it's complete
        session()->forget('onboarding_token');
        return redirect()->route('tenant.login', ['subdomain' => $tenant->domain])
            ->with('success', 'Welcome! Your workspace is ready.');

        //     return redirect()->to("http://{$tenant->domain}.myapp.test:8000/login")
        // ->with('success', 'Welcome! Your workspace is ready.');
    }
}
