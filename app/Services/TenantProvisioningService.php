<?php

namespace App\Services;

use App\Models\OnboardingSession;
use App\Models\Tenant;
use App\Jobs\ProvisionTenantJob;
use Illuminate\Support\Facades\DB;

class TenantProvisioningService
{
    public function provision(OnboardingSession $session): Tenant
    {
        // idempotent if tenant already exists return it
        $tenant = Tenant::where('domain', strtolower($session->subdomain))->first();

        if (! $tenant) {
            DB::beginTransaction();
            try {
                $tenant = Tenant::create([
                    'name' => $session->company_name,
                    'domain'    => strtolower($session->subdomain),
                    'email'        => $session->email,
                    'database'     => 'tenant_' . strtolower($session->subdomain) . '_db',
                    'status' => 'provisioning',

                ]);

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
        ProvisionTenantJob::dispatch($tenant, $session)->onQueue('provisioning');

        return $tenant;
    }
}