<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('onboarding_sessions', function (Blueprint $table) {
            /* 
                Fields are nullable because they are not required for the initial onboarding process.
                we are building a multistep onboarding.
                at each step only a few fields are filled.
                we cant require all fields up front each step saves only that step's data.
            */
            $table->id();
            $table->uuid('token')->unique();
            $table->string('full_name')->nullable();
            $table->string('email')->unique()->nullable();     
            $table->string('password')->nullable();  
            $table->string('company_name')->nullable(); 
            $table->string('subdomain')->nullable();    
            $table->string('industry')->nullable();     
            $table->string('company_size')->nullable(); 
            $table->string('logo_path')->nullable();    
            $table->string('billing_name')->nullable();    
            $table->text('billing_address')->nullable();   
            $table->string('country')->nullable();         
            $table->string('phone')->nullable();           
            $table->boolean('is_complete')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_sessions');
    }
};
