<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\OnboardingSession;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\NotTenantAware;

class ProvisionTenantJob implements ShouldQueue, NotTenantAware
{
    use InteractsWithQueue, SerializesModels, Queueable;
    
    public int $tenantId;
    public int $sessionId;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant, OnboardingSession $session)
    {
        $this->tenantId = $tenant->id;
        $this->sessionId = $session->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            Log::info('starting provisioning', ['tenantId' => $this->tenantId]);

            // Load models fresh from database
            $tenant = Tenant::find($this->tenantId);
            $session = OnboardingSession::find($this->sessionId);
            
            if (!$tenant || !$session) {
                throw new \Exception('Tenant or session not found');
            }

            $tenant->update(['status' => 'provisioning']);

            $dbName = $tenant->database;
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");
            
            // dynamically configure tenant DB
            config([
                'database.connections.tenant.database' => $dbName,
            ]);

            // config()->set("database.connections.tenant", [
            //     'driver'    => 'mysql',
            //     'host'      => env('DB_HOST'),
            //     'port'      => env('DB_PORT', 3306),
            //     'database'  => $dbName,
            //     'username'  => env('DB_USERNAME'),
            //     'password'  => env('DB_PASSWORD'),
            //     'charset'   => 'utf8mb4',
            //     'collation' => 'utf8mb4_unicode_ci',
            // ]);

            DB::purge('tenant');
            DB::reconnect('tenant');

            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force'    => true,
            ]);

            $existingUser = DB::connection('tenant')->table('users')->where('email', $session->email)->first();
            if (! $existingUser) {
                DB::connection('tenant')->table('users')->insert([
                    'name'       => $session->full_name,
                    'email'      => $session->email,
                    'password'   => Hash::needsRehash($session->password)
                        ? Hash::make($session->password)
                        : $session->password,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Check if settings table exists before working with it
            $settingsTableExists = DB::connection('tenant')
                ->getSchemaBuilder()
                ->hasTable('settings');

            if ($settingsTableExists) {
                $existingSettings = DB::connection('tenant')->table('settings')->pluck('key')->toArray();
                $defaults = [
                    ['key' => 'timezone', 'value' => 'UTC'],
                    ['key' => 'locale', 'value' => 'en'],
                ];

                foreach ($defaults as $setting) {
                    if (! in_array($setting['key'], $existingSettings)) {
                        DB::connection('tenant')->table('settings')->insert([
                            ...$setting,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            //complete onboarding session
            $session->is_complete = true;
            $session->save();


            // Done
            $tenant->update(['status' => 'active']);

        } catch (\Throwable $e) {
            Log::error("Tenant provisioning failed: {$e->getMessage()}", ['tenant_id' => $this->tenantId]);
            if (isset($tenant)) {
                $tenant->update(['status' => 'failed']);
            }
            $this->fail($e);
        }




        // $session = OnboardingSession::find($this->sessionId);
        // $tenant = Tenant::create([
        //     'name' => $session->company_name,
        //     'subdomain' => $session->subdomain,
        //     'database' => 'tenant_'.$session->subdomain,
        //     'status' => 'provisioning',
        // ]);
        // //creating database on tenant subdomain
        // DB::statement('CREATE DATABASE IF NOT EXISTS '.$tenant->database);

        // //dynamically config tenant db
        // config()->set('database.connections.tenant', [
        //     'driver' => 'mysql',
        //     'host' => env('DB_host'),
        //     'database' => $tenant->database,
        //     'username' => env('DB_USERNAME'),            
        //     'password' => env('DB_PASSWORD')
        // ]);

        // DB::purge('tenant');
        // DB::reconnect('tenant');
        
        // //run migrations on tenant db
        // Artisan::call('tenants:artisan',[
        //     'artisanCommand' => 'migrate --database=tenant',
        //     '--tenants' => $tenant->id,
        // ]);

        // DB::connection('tenant')->insert([
        //     'name' => $session->full_name,
        //     'email' => $session->email,
        //     'password' => $session->password,
        //     'created_at' => now(),
        // ]);

        // $tenant->update([
        //     'status' => 'active',
        // ]);

    }
}
