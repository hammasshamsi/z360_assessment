<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingSession extends Model
{
     protected $fillable = [
        'token',
        'full_name',
        'email',
        'password',
        'company_name',
        'subdomain',
        'industry',
        'company_size',
        'logo_path',
        'billing_name',
        'billing_address',
        'country',
        'phone',
        'is_complete',
    ];
}
