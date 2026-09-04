<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MockInterviewAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_clicking_disabled_mock_interview_redirects_with_restricted_message()
    {
        SystemSetting::set('ai_mock_interview_enabled', '0');

        $response = $this->get(route('mock-interviews.index'));

        $response->assertRedirect(route('welcome'));
        $response->assertSessionHas('error', 'This feature has been restricted by the administrator.');
    }

    public function test_authenticated_user_accessing_disabled_mock_interview_redirects_with_restricted_message()
    {
        SystemSetting::set('ai_mock_interview_enabled', '0');
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('mock-interviews.index'));

        $response->assertRedirect(route('welcome'));
        $response->assertSessionHas('error', 'This feature has been restricted by the administrator.');
    }

    public function test_public_user_accessing_enabled_mock_interview_redirects_to_login()
    {
        SystemSetting::set('ai_mock_interview_enabled', '1');

        $response = $this->get(route('mock-interviews.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_accessing_enabled_mock_interview_sees_index()
    {
        SystemSetting::set('ai_mock_interview_enabled', '1');
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('mock-interviews.index'));

        $response->assertStatus(200);
        $response->assertSee('AI Mock Interviews Engine');
    }
}
