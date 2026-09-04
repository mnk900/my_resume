<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_registration_screen(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_registration_screen(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/register');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_registration_screen(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/register');

        $response->assertStatus(200);
    }

    public function test_admin_can_register_new_portfolio_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/register', [
            'name' => 'Candidate User',
            'email' => 'candidate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'candidate@example.com',
            'name' => 'Candidate User',
        ]);

        $createdUser = User::where('email', 'candidate@example.com')->first();
        $this->assertNotNull($createdUser->portfolio);
        $this->assertAuthenticatedAs($admin);
    }
}
