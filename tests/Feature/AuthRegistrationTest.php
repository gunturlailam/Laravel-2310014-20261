<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_stores_phone_and_hashed_password(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'no_hp' => '08123456789',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertSame('08123456789', $user->no_hp);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }
}
