<?php

namespace Tests\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can register with valid data.
     */
    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'name' => 'Test',
            'surname' => 'User',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);
    }

    /**
     * Test that registration fails with invalid email.
     */
    public function test_registration_fails_with_invalid_email()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'name' => 'Test',
            'surname' => 'User',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test that registration fails when password confirmation doesn't match.
     */
    public function test_registration_fails_when_password_confirmation_does_not_match()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword123!',
            'name' => 'Test',
            'surname' => 'User',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
