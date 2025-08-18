<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

// NO LONGER NEEDED
class AuthController extends Controller
{
    // public function showLoginForm()
    // {
    //     SpatieTenant::forgetCurrent();
    //     config(['database.default' => 'landlord']);
    //     return view('landlord.auth');
    // }

    // public function login(Request $request)
    // {
    //     SpatieTenant::forgetCurrent();
    //     config(['database.default' => 'landlord']);

    //     $credentials = $request->only('email', 'password');
    //     if (Auth::guard('admin')->attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended(route('landlord.dashboard'));
    //     }
    //     return back()->withErrors(['email' => 'Invalid credentials']);
    // }

    // public function logout(Request $request)
    // {
    //     Auth::guard('admin')->logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return redirect()->route('login');
    // }
}
