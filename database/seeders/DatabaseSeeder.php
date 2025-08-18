<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // more better if we store in env file and call here
        DB::table('admins')->updateOrInsert(
            ['email' => 'admin@myapp.test'],
            ['password' => Hash::make('shamsi@123')],
            ['created_at' => now()], 
            ['updated_at' => now()]
        );
    }
}
