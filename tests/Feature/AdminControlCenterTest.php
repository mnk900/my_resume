<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControlCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_user_cannot_access_admin_control_center()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.index'));
        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.index'));
        $response->assertStatus(200);
        $response->assertSee('Operational Command Center');
    }

    public function test_admin_can_access_verification_center()
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.verification.index'));
        $response->assertStatus(200);
        $response->assertSee('Verification Control Center');
    }

    public function test_admin_can_access_professionals_management()
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.professionals.index'));
        $response->assertStatus(200);
        $response->assertSee('Professional Management');
    }

    public function test_admin_can_access_companies_management_and_update_status()
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $company = Company::create([
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.companies.status', $company->id), [
            'status' => 'verified',
        ]);

        $response->assertRedirect();
        $this->assertEquals('verified', $company->fresh()->verification_status);

        // Verify audit log creation
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'company.verified',
            'target_type' => Company::class,
            'target_id' => $company->id,
        ]);
    }

    public function test_admin_can_suspend_user_and_creates_audit_log()
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->post(route('admin.professionals.suspend', $user->id), [
            'reason' => 'Violation of terms',
        ]);

        $response->assertRedirect();
        $this->assertEquals('suspended', $user->fresh()->account_status);

        // Suspended user can no longer access admin or user routes
        $suspendedResponse = $this->actingAs($user->fresh())->get(route('portfolio.edit'));
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user.suspended',
            'target_type' => User::class,
            'target_id' => $user->id,
        ]);
    }

    public function test_admin_can_view_audit_logs()
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Administrative Audit Log');
    }
}
