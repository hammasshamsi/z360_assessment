<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tenant;
use App\Jobs\ProvisionTenantJob;
use App\Models\OnboardingSession;
use Illuminate\Support\Facades\DB;

class TenantProvisioningTest extends TestCase
{
     use RefreshDatabase;

    public function test_tenant_provisioning_job_creates_tenant_database()
    {
        $session = OnboardingSession::create([
            'token' => 'testtoken',
            'full_name' => 'shamsi',
            'email' => 'test@shamsi.com',
            'password' => bcrypt('secure_password'),
            'company_name' => 'test inc',
            'subdomain' => 'testinc',
            'billing_name' => 'shamsi',
            'billing_email' => 'test@shamsi.com',
            'billing_address' => 'test address',
            'country' => 'PK',
            'is_complete' => false,
        ]);

        $db_name = 'tenant_test_db';
        $tenant = Tenant::create([
            'name' => 'test inc',
            'domain' => 'testinc',
            'database' => $db_name,
            'email' => 'test@shamsi.com',
            'status' => 'provisioning',
        ]);

        ProvisionTenantJob::dispatchSync($tenant, $session);

        // assert tenant status is updated, database exists, etc.
        $this->assertEquals('active', $tenant->fresh()->status);

        DB::statement("DROP DATABASE IF EXISTS `$db_name`");
    }
}
