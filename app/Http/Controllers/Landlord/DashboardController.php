<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\OnboardingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ensuring we are in landlord context tis is important to avoid tenant context issues
        SpatieTenant::forgetCurrent();
        
        // forece landlord db conntection
        config(['database.default' => 'landlord']);
        
        // tenant statistics for the dashboard
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'pending_tenants' => Tenant::where('status', 'pending')->count(),
            'provisioning_tenants' => Tenant::where('status', 'provisioning')->count(),
            'failed_tenants' => Tenant::where('status', 'failed')->count(),
            'total_sessions' => OnboardingSession::count(),
            'completed_sessions' => OnboardingSession::where('is_complete', true)->count(),
        ];
        
        $query = Tenant::query();
        
        // filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // serach if applicable
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('database', 'like', "%{$search}%");
            });
        }
        
        $tenants = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        // // recent onboarding session
        // $recentSessions = OnboardingSession::orderBy('created_at', 'desc')->take(10)->get();

        $recentSessions = OnboardingSession::orderBy('created_at', 'desc')->take(5)->get()
            ->map(function ($session) {
                // Add the signed resume url to each session object
                if (!$session->is_complete) {
                    $session->resume_url = URL::signedRoute('onboarding.resume', ['token' => $session->token]);
                }
                return $session;
            });
        
        // return view('landlord.dashboard', compact('stats', 'tenants', 'recentSessions'));
        return Inertia::render('Landlord/Dashboard', [
            'stats' => $stats,
            'tenants' => $tenants,
            'recentSessions' => $recentSessions,
            'filters' => $request->only(['search', 'status']), // pass filters back to the view
            'db_context' => DB::connection()->getDatabaseName(),
            'spatie_context' => SpatieTenant::current() ? 'TENANT' : 'LANDLORD',

        ]);
    }
    
    public function tenantDetails(Tenant $tenant)
    {
        // ensuring we are in landlord context tis is important to avoid tenant context issues
        SpatieTenant::forgetCurrent();
        
        // get aassiciated onboarding session
        $session = OnboardingSession::where('subdomain', $tenant->domain)->first();
        
        // get tenant stats if active
        $tenantStats = null;
        if ($tenant->status === 'active') {
            $tenantStats = $this->getTenantDatabaseStats($tenant);
        }
        
        return view('landlord.tenant-details', compact('tenant', 'session', 'tenantStats'));
    }
    
    public function deleteTenant(Tenant $tenant)
    {
        // ensuring we are in landlord context tis is important to avoid tenant context issues
        SpatieTenant::forgetCurrent();
        
        try {
            // drop tenatn database if exists
            if ($tenant->status === 'active') {
                DB::statement("DROP DATABASE IF EXISTS `{$tenant->database}`");
            }
            
            // delete associated onboarding session
            OnboardingSession::where('subdomain', $tenant->domain)->delete();
            
            // Delete tenant record
            $tenant->delete();
            
            return redirect()->route('landlord.dashboard')->with('success', 'Tenant deleted successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete tenant: ' . $e->getMessage());
        }
    }
    
    private function getTenantDatabaseStats(Tenant $tenant)
    {
        try {
            // temporarily switch to tenant context in order to get stats
            $tenant->makeCurrent();
            
            $stats = [
                'users_count' => DB::connection('tenant')->table('users')->count(),
                'database_size' => $this->getDatabaseSize($tenant->database),
                'tables_count' => count(DB::connection('tenant')->select('SHOW TABLES')),
            ];
            
            // returning to landlord context
            SpatieTenant::forgetCurrent();
            
            return $stats;
        } catch (\Exception $e) {
            SpatieTenant::forgetCurrent();
            return ['error' => $e->getMessage()];
        }
    }
    
    private function getDatabaseSize($databaseName)
    {
        try {
            $result = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [$databaseName]);
            
            return ($result[0]->size_mb ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}