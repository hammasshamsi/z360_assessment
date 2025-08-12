<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class LandlordAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ensure we are always in landlord context
        SpatieTenant::forgetCurrent();
        // for assessment we use simple check
        if (!session('landlord_authenticated')) {
            if ($request->input('admin_password') === 'admin123') {
                session(['landlord_authenticated' => true]);
                return redirect($request->url());
            }
            return response()->view('landlord.auth', [], 401);
        }
        return $next($request);
    }
}
