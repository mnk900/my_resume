<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default themes
        Theme::create(['name' => 'Premium Theme', 'slug' => 'premium', 'is_active' => true]);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard_with_stats(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']); // Auto-creates a portfolio

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        
        // Check statistics and view data structure
        $response->assertViewHas('stats');
        $response->assertViewHas('messages');
        $response->assertSee('System Analytics');
        $response->assertSee('User Management');
        $response->assertSee('Recent Platform Notifications');
        $response->assertSee($admin->name);
        $response->assertSee($user->name);
    }

    public function test_admin_can_toggle_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->post("/admin/users/{$user->id}/toggle-role");
        $response->assertRedirect();
        
        $this->assertEquals('admin', $user->fresh()->role);

        $response2 = $this->actingAs($admin)->post("/admin/users/{$user->id}/toggle-role");
        $response2->assertRedirect();
        
        $this->assertEquals('user', $user->fresh()->role);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post("/admin/users/{$admin->id}/toggle-role");
        $response->assertRedirect();
        $response->assertSessionHas('error', 'demote-self-blocked');
        
        $this->assertEquals('admin', $admin->fresh()->role);
    }

    public function test_admin_can_toggle_user_verification(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'email_verified_at' => null]);

        $response = $this->actingAs($admin)->post("/admin/users/{$user->id}/toggle-verification");
        $response->assertRedirect();
        $this->assertNotNull($user->fresh()->email_verified_at);

        $response2 = $this->actingAs($admin)->post("/admin/users/{$user->id}/toggle-verification");
        $response2->assertRedirect();
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_admin_can_toggle_portfolio_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $portfolio = $user->portfolio;
        
        $this->assertTrue((bool) $portfolio->is_active);

        $response = $this->actingAs($admin)->post("/admin/portfolio/{$portfolio->id}/toggle");
        $response->assertRedirect();
        $this->assertFalse((bool) $portfolio->fresh()->is_active);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");
        $response->assertRedirect();
        
        $this->assertNull(User::find($user->id));
        $this->assertNull(Portfolio::where('user_id', $user->id)->first());
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error', 'delete-self-blocked');
        
        $this->assertNotNull(User::find($admin->id));
    }
}
