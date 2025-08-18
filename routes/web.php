<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Onboarding\ResumeController;
use App\Http\Controllers\Onboarding\Step1Controller;
use App\Http\Controllers\Onboarding\Step2Controller;
use App\Http\Controllers\Onboarding\Step3Controller;
use App\Http\Controllers\Onboarding\Step4Controller;
use App\Http\Controllers\Onboarding\Step5Controller;
use App\Http\Controllers\Landlord\DashboardController as LandlordDashboardController;
use App\Http\Controllers\Tenant\AuthController as TenantAuthController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| These routes are soo bulky, we can instead implement business logic in   
| controller to keep the routes file clean and readable especially 
| tenant routes.
|--------------------------------------------------------------------------
*/

Route::domain('myapp.test')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    })->name('welcome');

    Route::get('/getstarted', function () {
        return Inertia::render('Onboarding/GetStarted');
    })->name('getstarted');

    Route::get('/onboarding/start-new', function () {
        session()->forget('onboarding_token');
        return redirect()->route('onboarding.step1');
    })->name('onboarding.new');

    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/step-1', [Step1Controller::class, 'show'])->name('step1');
        Route::post('/step-1', [Step1Controller::class, 'store']);

        Route::get('/step-2', [Step2Controller::class, 'show'])->name('step2')->middleware('signed');
        Route::post('/step-2', [Step2Controller::class, 'store'])->middleware('onboarding.progress');

        Route::get('/step-3', [Step3Controller::class, 'show'])->name('step3')->middleware('signed');
        Route::post('/step-3', [Step3Controller::class, 'store'])->middleware('onboarding.progress');

        Route::get('/step-4', [Step4Controller::class, 'show'])->name('step4')->middleware('signed');
        Route::post('/step-4', [Step4Controller::class, 'store'])->middleware('onboarding.progress');

        Route::get('/step-5', [Step5Controller::class, 'show'])->name('step5')->middleware('signed');
        Route::post('/step-5', [Step5Controller::class, 'store'])->name('step5.store');

        Route::get('resume', ResumeController::class)->name('resume')->middleware('onboarding.progress');

        Route::get('/success/{tenant}', function($tenantId) {
            $tenant = Tenant::findOrFail($tenantId);
            return Inertia::render('Onboarding/Success', ['tenant' => $tenant]);
        })->name('success');
    });
});


/*
|--------------------------------------------------------------------------
| Landlord Environment (landlord.myapp.test)
|--------------------------------------------------------------------------
*/

Route::domain('landlord.myapp.test')->group(function () {
    require __DIR__.'/auth.php';

    Route::middleware('auth:admin')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/', fn() => redirect()->route('landlord.dashboard'));

    Route::prefix('landlord')->name('landlord.')->middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [LandlordDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tenant/{tenant}', [LandlordDashboardController::class, 'tenantDetails'])->name('tenant.details');
        Route::delete('/tenant/{tenant}', [LandlordDashboardController::class, 'deleteTenant'])->name('tenant.delete');
    });
});

/*
|--------------------------------------------------------------------------
| Tenant Environments ({tenant}.myapp.test)
|--------------------------------------------------------------------------
*/
Route::domain('{subdomain}.myapp.test')->group(function () {
    Route::get('/', function ($subdomain) {
         return redirect()->route('tenant.login', $subdomain);
    })->name('tenant.home');

    Route::get('/login', function ($subdomain) {
        $tenant = Tenant::where('domain', $subdomain)->firstOrFail();

        if ($tenant->status !== 'active') {
            return Inertia::render('Tenant/Inactive', ['tenant' => $tenant]);
        }

        $tenant->makeCurrent();
        config(['database.default' => 'tenant']);
        config(['database.connections.tenant.database' => $tenant->database]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        return Inertia::render('Tenant/Login', [
            'tenant' => $tenant->only('name', 'domain', 'status'),
            'status' => session('status'),
            'db_connection' => DB::connection('tenant')->getDatabaseName(),
        ]);
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

        // manually log the user in by storing their id in the session.
        session(['tenant_user_id' => $user->id]);

        return redirect()->route('tenant.dashboard', $subdomain);
    })->name('tenant.login.submit');

    
        Route::get('/dashboard', function ($subdomain) {
            // check for authentication
            if (!session()->has('tenant_user_id')) {
                return redirect()->route('tenant.login', $subdomain);
            }

            $tenant = Tenant::where('domain', $subdomain)->firstOrFail();
        
            if ($tenant->status !== 'active') {
                session()->forget('tenant_user_id');
                return redirect()->route('tenant.login', $subdomain);
            }
            
            $tenant->makeCurrent();
            config(['database.default' => 'tenant']);
            config(['database.connections.tenant.database' => $tenant->database]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            // retrieve auth user
            $user = DB::table('users')->find(session('tenant_user_id'));

            // force logout
            if (!$user) {
                session()->forget('tenant_user_id');
                return redirect()->route('tenant.login', $subdomain);
            }
        
            $authUser = new User((array)$user);
            // data needed for detailed dashboard
            $dashboardData = [
                'tenant' => $tenant,
                'auth' => ['user' => $authUser],
                'context' => [
                    'db_connection' => DB::connection('tenant')->getDatabaseName(),
                    'db_driver' => config('database.connections.tenant.driver'),
                    'default_connection' => config('database.default'),
                    'landlord_connection' => config('multitenancy.landlord_database_connection_name'),
                    'current_tenant_id' => app('currentTenant')?->id,
                    'users_count' => DB::connection('tenant')->table('users')->count(),
                ]
            ];

            return Inertia::render('Tenant/Dashboard', $dashboardData);
        })->name('tenant.dashboard');

        Route::post('/logout', function ($subdomain) {
            session()->forget('tenant_user_id');
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('tenant.login', $subdomain);
        })->name('tenant.logout');
});