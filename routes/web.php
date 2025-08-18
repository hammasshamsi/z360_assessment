<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Onboarding\ResumeController;
use App\Http\Controllers\Onboarding\Step1Controller;
use App\Http\Controllers\Onboarding\Step2Controller;
use App\Http\Controllers\Onboarding\Step3Controller;
use App\Http\Controllers\Onboarding\Step4Controller;
use App\Http\Controllers\Onboarding\Step5Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Landlord\DashboardController;

/*
|--------------------------------------------------------------------------
| These routes are soo bulky, we can instead implement business logic in   
| controller to keep the routes file clean and readable especially 
| tenant routes.
|--------------------------------------------------------------------------
*/

Route::domain('myapp.test')->group(function () {
    Route::get('/', function () { return view('welcome'); })->name('welcome');
    Route::get('/getstarted', function () { return view('onboarding.getStarted'); })->name('getstarted');
    Route::get('/onboarding/start-new', function () {
        session()->forget('onboarding_token');
        return redirect()->route('onboarding.step1');
    })->name('onboarding.new');

    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('resume', ResumeController::class)->name('resume')->middleware('onboarding.progress');
        Route::get('/step-1', [Step1Controller::class, 'show'])->name('step1');
        Route::post('/step-1', [Step1Controller::class, 'store']);
        Route::get('/step-2', [Step2Controller::class, 'show'])->name('step2')->middleware('signed');
        Route::post('/step-2', [Step2Controller::class, 'store'])->middleware('onboarding.progress');
        Route::get('/step-3', [Step3Controller::class, 'show'])->name('step3')->middleware('signed');
        Route::post('/step-3', [Step3Controller::class, 'store'])->middleware('onboarding.progress');
        Route::get('/step-4', [Step4Controller::class, 'show'])->name('step4')->middleware('signed');
        Route::post('/step-4', [Step4Controller::class, 'store'])->middleware('onboarding.progress');
        Route::get('/step-5', [Step5Controller::class, 'show'])->name('step5')->middleware('signed');
        Route::post('/step-5', [Step5Controller::class, 'store']);
        Route::get('/success/{tenant}', function($tenantId) {
            $tenant = Tenant::findOrFail($tenantId);
            return view('onboarding.success', compact('tenant'));
        })->name('success');
    });
});

/*
|--------------------------------------------------------------------------
| Landlord Environment (landlord.myapp.test)
|--------------------------------------------------------------------------
*/
Route::domain('landlord.myapp.test')->group(function () {
    Route::get('/', fn() => redirect()->route('landlord.dashboard'));

    Route::prefix('landlord')->name('landlord.')->middleware(['landlord.auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', function() {
            session()->forget('landlord_authenticated');
            return redirect()->route('landlord.dashboard');
        })->name('logout');
        Route::get('/tenant/{tenant}', [DashboardController::class, 'tenantDetails'])->name('tenant.details');
        Route::delete('/tenant/{tenant}', [DashboardController::class, 'deleteTenant'])->name('tenant.delete');
    });
});


/*
|--------------------------------------------------------------------------
| Tenant Environment (tenant.myapp.test)
|--------------------------------------------------------------------------
*/

Route::domain('{subdomain}.myapp.test')->group(function () {
    Route::get('/', function ($subdomain) {
        return redirect()->route('tenant.login', $subdomain);
    });
    
    Route::get('/login', function ($subdomain) {
        $tenant = Tenant::where('domain', $subdomain)->firstOrFail();
        
        if ($tenant->status !== 'active') {
            return view('tenant.inactive', compact('tenant'));
        }
        
        $tenant->makeCurrent();
        config(['database.default' => 'tenant']);
        config(['database.connections.tenant.database' => $tenant->database]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        return view('tenant.login', compact('tenant'));
    })->name('tenant.login');
    
    Route::post('/login', function ($subdomain) {
        $tenant = Tenant::where('domain', $subdomain)->firstOrFail();
        
        if ($tenant->status !== 'active') {
            return back()->withErrors(['status' => 'Workspace not activated.'])->withInput();
        }
        
        $tenant->makeCurrent();
        config(['database.default' => 'tenant']);
        config(['database.connections.tenant.database' => $tenant->database]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = DB::connection('tenant')
                  ->table('users')
                  ->where('email', $credentials['email'])
                  ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        return redirect()->route('tenant.dashboard', $subdomain);
    })->name('tenant.login.submit');
    
    Route::get('/dashboard', function ($subdomain) {
        $tenant = Tenant::where('domain', $subdomain)->firstOrFail();
        
        if ($tenant->status !== 'active') {
            return redirect()->route('tenant.login', $subdomain);
        }
        
        $tenant->makeCurrent();
        config(['database.default' => 'tenant']);
        config(['database.connections.tenant.database' => $tenant->database]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        
        return view('tenant.dashboard', compact('tenant'));
    })->name('tenant.dashboard');
});