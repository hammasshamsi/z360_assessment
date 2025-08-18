<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\OnboardingSession;

class OnboardingProgressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_step3_without_completing_step2()
    {
        $session = OnboardingSession::create([
            'token' => 'testtoken',
            'full_name' => 'tester',
            'email' => 'testing@example.com',
            //skipping passwords
        ]);

        $response = $this->get(route('onboarding.step3', ['token' => 'testtoken']));
        $response->assertRedirect(route('onboarding.step2'));
        $response->assertSessionHasErrors();
    }
}
