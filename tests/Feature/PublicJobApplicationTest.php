<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Opportunity;
use App\Models\JobApplication;
use App\Mail\PublicApplicationReceivedMail;
use App\Mail\NewPublicApplicationNotificationMail;
use App\Mail\ApplicationStatusEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicJobApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_application_form_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $opportunity = Opportunity::create([
            'posted_by_user_id' => $user->id,
            'type' => 'job',
            'title' => 'Senior Laravel Developer',
            'slug' => 'senior-laravel-developer',
            'description' => 'Test Job Description',
            'min_experience' => 3,
            'location_type' => 'remote',
            'employment_type' => 'full-time',
            'vacancies_count' => 1,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('opportunities.apply_public', $opportunity->slug));

        $response->assertStatus(200);
        $response->assertSee('Senior Laravel Developer');
        $response->assertSee('Candidate Job Application Form');
        $response->assertSee('Share Application Form on Social Media');
    }

    public function test_public_applicant_can_submit_job_application_with_cv_and_details()
    {
        Mail::fake();
        Storage::fake('public');

        $user = User::factory()->create();
        $opportunity = Opportunity::create([
            'posted_by_user_id' => $user->id,
            'type' => 'job',
            'title' => 'Frontend Engineer',
            'slug' => 'frontend-engineer',
            'description' => 'Frontend Job Description',
            'min_experience' => 2,
            'location_type' => 'onsite',
            'employment_type' => 'full-time',
            'vacancies_count' => 1,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $resumeFile = UploadedFile::fake()->create('my_resume.pdf', 500, 'application/pdf');
        $coverLetterFile = UploadedFile::fake()->create('cover_letter.pdf', 300, 'application/pdf');

        $payload = [
            'applicant_name' => 'Alice Johnson',
            'applicant_email' => 'alice@example.com',
            'applicant_phone' => '+923001234567',
            'is_currently_employed' => '1',
            'current_designation' => 'UI Designer',
            'current_organization_name' => 'Tech Corp',
            'current_organization_address' => 'Floor 2, Tech Building',
            'cover_letter' => 'I am very excited to apply for this frontend engineering position.',
            'cover_letter_file' => $coverLetterFile,
            'resume_file' => $resumeFile,
        ];

        $response = $this->post(route('opportunities.apply_public.store', $opportunity->id), $payload);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('job_applications', [
            'opportunity_id' => $opportunity->id,
            'is_public_applicant' => 1,
            'applicant_name' => 'Alice Johnson',
            'applicant_email' => 'alice@example.com',
            'applicant_phone' => '+923001234567',
            'is_currently_employed' => 1,
            'current_designation' => 'UI Designer',
            'current_organization_name' => 'Tech Corp',
        ]);

        Mail::assertSent(PublicApplicationReceivedMail::class, function ($mail) {
            return $mail->hasTo('alice@example.com');
        });

        Mail::assertSent(NewPublicApplicationNotificationMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_job_owner_can_view_public_applicant_details_and_send_email()
    {
        Mail::fake();

        $user = User::factory()->create();
        $opportunity = Opportunity::create([
            'posted_by_user_id' => $user->id,
            'type' => 'job',
            'title' => 'Backend Engineer',
            'slug' => 'backend-engineer',
            'description' => 'Backend Job Description',
            'min_experience' => 3,
            'location_type' => 'remote',
            'employment_type' => 'full-time',
            'vacancies_count' => 1,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $application = JobApplication::create([
            'opportunity_id' => $opportunity->id,
            'is_public_applicant' => true,
            'applicant_name' => 'Bob Smith',
            'applicant_email' => 'bob@example.com',
            'applicant_phone' => '+923009876543',
            'is_currently_employed' => true,
            'current_designation' => 'PHP Developer',
            'current_organization_name' => 'Software House',
            'current_organization_address' => 'Main Street, City',
            'cover_letter' => 'My cover letter text.',
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        // Access applicant review page as job owner
        $response = $this->actingAs($user)->get(route('applications.show', $application->id));
        $response->assertStatus(200);
        $response->assertSee('Bob Smith');
        $response->assertSee('bob@example.com');
        $response->assertSee('PHP Developer');
        $response->assertSee('Software House');

        // Send email to candidate
        $emailPayload = [
            'subject' => 'Interview Invitation for Backend Engineer',
            'body' => 'Dear Bob, we would love to invite you for an interview next Monday.',
            'email_type' => 'interview',
        ];

        $emailResponse = $this->actingAs($user)->post(route('applications.send-email', $application->id), $emailPayload);
        $emailResponse->assertSessionHas('success');

        Mail::assertSent(ApplicationStatusEmail::class, function ($mail) {
            return $mail->hasTo('bob@example.com');
        });
    }
}
