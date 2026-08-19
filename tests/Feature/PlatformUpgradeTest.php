<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\JobApplication;
use App\Services\JobMatchingService;
use App\Services\MockInterviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformUpgradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_company_profile()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->post(route('companies.store'), [
            'name' => 'Acme Innovations',
            'description' => 'Leading software development firm.',
            'email' => 'contact@acme.test',
            'industry' => 'Software',
            'city' => 'San Francisco',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'name' => 'Acme Innovations',
            'verification_status' => 'pending',
        ]);
        $this->assertDatabaseHas('company_members', [
            'user_id' => $user->id,
            'role' => 'owner',
        ]);
    }

    public function test_company_can_post_opportunity()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $company = Company::create([
            'name' => 'TechCorp',
            'slug' => 'techcorp',
            'email' => 'hr@techcorp.test',
            'description' => 'Tech company',
        ]);
        $company->users()->attach($user->id, ['role' => 'owner']);

        $response = $this->actingAs($user)->post(route('opportunities.store'), [
            'company_id' => $company->id,
            'type' => 'job',
            'title' => 'Senior Backend Developer',
            'description' => 'Join our backend core team to build APIs.',
            'min_experience' => 3,
            'location_type' => 'remote',
            'employment_type' => 'full-time',
            'vacancies_count' => 2,
            'skills' => 'PHP, Laravel, MySQL',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('opportunities', [
            'title' => 'Senior Backend Developer',
            'company_id' => $company->id,
            'status' => 'published',
        ]);
    }

    public function test_candidate_can_apply_and_match_score_is_calculated()
    {
        $candidate = User::factory()->create(['email_verified_at' => now()]);
        $company = Company::create(['name' => 'DevCo', 'slug' => 'devco', 'description' => 'Dev']);
        
        $job = Opportunity::create([
            'company_id' => $company->id,
            'posted_by_user_id' => $candidate->id,
            'type' => 'job',
            'title' => 'Full Stack Engineer',
            'slug' => 'full-stack-engineer',
            'description' => 'Full stack job position.',
            'min_experience' => 2,
            'location_type' => 'remote',
            'employment_type' => 'full-time',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($candidate)->post(route('applications.store', $job->id), [
            'cover_letter' => 'Excited to apply for this position!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_applications', [
            'opportunity_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'applied',
        ]);
    }

    public function test_ai_mock_interview_session_can_be_generated_and_evaluated()
    {
        $candidate = User::factory()->create(['email_verified_at' => now()]);
        
        $service = new MockInterviewService();
        $session = $service->generateSession($candidate, null, 'Software Architect');

        $this->assertCount(5, $session->questions);
        $this->assertEquals('in_progress', $session->status);

        $answers = [];
        foreach ($session->questions as $q) {
            $answers[$q->id] = "I approach this using modular clean architecture, SOLID design principles, unit testing, and robust microservices monitoring.";
        }

        $evaluated = $service->evaluateAnswers($session, $answers);

        $this->assertEquals('completed', $evaluated->status);
        $this->assertGreaterThanOrEqual(50, $evaluated->overall_score);
    }
}
