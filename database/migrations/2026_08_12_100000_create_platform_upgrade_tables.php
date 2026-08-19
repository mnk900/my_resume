<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add user_type to users table
        if (!Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('user_type')->default('professional')->after('role');
            });
        }

        // 2. Companies table
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo_path')->nullable();
                $table->string('cover_path')->nullable();
                $table->string('industry')->nullable();
                $table->string('org_type')->nullable();
                $table->text('description')->nullable();
                $table->string('website')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->json('social_links')->nullable();
                $table->string('company_size')->nullable();
                $table->integer('founded_year')->nullable();
                $table->string('verification_status')->default('pending'); // pending, verified, rejected, suspended
                $table->timestamp('verified_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 3. Company Members table
        if (!Schema::hasTable('company_members')) {
            Schema::create('company_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('role')->default('member'); // owner, recruiter, member
                $table->string('title')->nullable();
                $table->timestamps();
                $table->unique(['company_id', 'user_id']);
            });
        }

        // 4. Opportunities table
        if (!Schema::hasTable('opportunities')) {
            Schema::create('opportunities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
                $table->foreignId('posted_by_user_id')->constrained('users')->onDelete('cascade');
                $table->string('type')->default('job'); // job, internship, freelance, training, workshop, scholarship, event, volunteer, other
                $table->string('title');
                $table->string('slug');
                $table->text('description');
                $table->text('responsibilities')->nullable();
                $table->string('category')->nullable();
                $table->string('industry')->nullable();
                $table->integer('min_experience')->default(0);
                $table->integer('max_experience')->nullable();
                $table->string('education_required')->nullable();
                $table->string('location_type')->default('onsite'); // onsite, remote, hybrid
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->string('employment_type')->default('full-time'); // full-time, part-time, contract, freelance, internship
                $table->decimal('salary_min', 12, 2)->nullable();
                $table->decimal('salary_max', 12, 2)->nullable();
                $table->string('salary_currency')->default('USD');
                $table->string('salary_period')->default('monthly'); // yearly, monthly, hourly
                $table->json('benefits')->nullable();
                $table->date('application_deadline')->nullable();
                $table->string('external_url')->nullable();
                $table->boolean('is_internal_application')->default(true);
                $table->string('status')->default('published'); // draft, published, paused, closed, archived
                $table->boolean('is_featured')->default(false);
                $table->integer('vacancies_count')->default(1);
                $table->timestamp('published_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 5. Opportunity Skills table
        if (!Schema::hasTable('opportunity_skills')) {
            Schema::create('opportunity_skills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
                $table->string('skill_name');
                $table->boolean('is_required')->default(true);
                $table->integer('weight')->default(1);
                $table->timestamps();
            });
        }

        // 6. Job Applications table
        if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('cover_letter')->nullable();
                $table->string('resume_version_path')->nullable();
                $table->string('status')->default('applied'); // applied, under_review, shortlisted, interview, selected, rejected, withdrawn
                $table->text('status_notes')->nullable();
                $table->decimal('match_score', 5, 2)->nullable();
                $table->timestamp('applied_at')->useCurrent();
                $table->timestamps();
                $table->unique(['opportunity_id', 'user_id']);
            });
        }

        // 7. Saved Opportunities table
        if (!Schema::hasTable('saved_opportunities')) {
            Schema::create('saved_opportunities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'opportunity_id']);
            });
        }

        // 8. Candidate Shortlists table
        if (!Schema::hasTable('candidate_shortlists')) {
            Schema::create('candidate_shortlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('opportunity_id')->nullable()->constrained('opportunities')->onDelete('cascade');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 9. Candidate Notes table
        if (!Schema::hasTable('candidate_notes')) {
            Schema::create('candidate_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
                $table->text('note');
                $table->timestamps();
            });
        }

        // 10. Professional Preferences table
        if (!Schema::hasTable('professional_preferences')) {
            Schema::create('professional_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
                $table->string('availability')->default('open_to_opportunities'); // open_to_work, open_to_opportunities, freelance, internship, not_looking
                $table->json('preferred_titles')->nullable();
                $table->json('preferred_industries')->nullable();
                $table->json('preferred_locations')->nullable();
                $table->string('remote_preference')->default('any'); // remote_only, hybrid, onsite, any
                $table->decimal('salary_expectation_min', 12, 2)->nullable();
                $table->string('salary_expectation_currency')->default('USD');
                $table->boolean('willing_to_relocate')->default(false);
                $table->timestamps();
            });
        }

        // 11. Posts table
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
                $table->foreignId('opportunity_id')->nullable()->constrained('opportunities')->onDelete('cascade');
                $table->foreignId('original_post_id')->nullable()->constrained('posts')->onDelete('cascade');
                $table->text('content');
                $table->string('image_path')->nullable();
                $table->string('post_type')->default('general'); // general, job_share, portfolio_update, achievement, company_update
                $table->string('status')->default('published'); // published, hidden
                $table->integer('likes_count')->default(0);
                $table->integer('comments_count')->default(0);
                $table->integer('shares_count')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 12. Post Likes table
        if (!Schema::hasTable('post_likes')) {
            Schema::create('post_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['post_id', 'user_id']);
            });
        }

        // 13. Post Comments table
        if (!Schema::hasTable('post_comments')) {
            Schema::create('post_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('parent_id')->nullable()->constrained('post_comments')->onDelete('cascade');
                $table->text('comment');
                $table->timestamps();
            });
        }

        // 14. Follows table
        if (!Schema::hasTable('follows')) {
            Schema::create('follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('followable_type');
                $table->unsignedBigInteger('followable_id');
                $table->timestamps();
                $table->index(['followable_type', 'followable_id']);
            });
        }

        // 15. System Notifications table
        if (!Schema::hasTable('system_notifications')) {
            Schema::create('system_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->string('action_url')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // 16. Mock Interviews table
        if (!Schema::hasTable('mock_interviews')) {
            Schema::create('mock_interviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('opportunity_id')->nullable()->constrained('opportunities')->onDelete('cascade');
                $table->string('job_title');
                $table->json('target_skills')->nullable();
                $table->string('status')->default('in_progress'); // in_progress, completed
                $table->integer('overall_score')->nullable();
                $table->integer('technical_score')->nullable();
                $table->integer('communication_score')->nullable();
                $table->string('readiness_rating')->nullable(); // High, Moderate, Needs Work
                $table->text('feedback_summary')->nullable();
                $table->json('detailed_report')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        // 17. Mock Interview Questions table
        if (!Schema::hasTable('mock_interview_questions')) {
            Schema::create('mock_interview_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mock_interview_id')->constrained('mock_interviews')->onDelete('cascade');
                $table->integer('question_number');
                $table->string('question_category'); // technical, role_specific, experience_based, behavioral, situational
                $table->text('question_text');
                $table->json('expected_key_points')->nullable();
                $table->text('user_answer')->nullable();
                $table->integer('score')->nullable();
                $table->text('feedback')->nullable();
                $table->text('sample_improved_answer')->nullable();
                $table->timestamps();
            });
        }

        // 18. Content Reports table
        if (!Schema::hasTable('content_reports')) {
            Schema::create('content_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
                $table->string('reportable_type');
                $table->unsignedBigInteger('reportable_id');
                $table->string('reason');
                $table->text('details')->nullable();
                $table->string('status')->default('pending'); // pending, reviewed, dismissed, actioned
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_reports');
        Schema::dropIfExists('mock_interview_questions');
        Schema::dropIfExists('mock_interviews');
        Schema::dropIfExists('system_notifications');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('post_likes');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('professional_preferences');
        Schema::dropIfExists('candidate_notes');
        Schema::dropIfExists('candidate_shortlists');
        Schema::dropIfExists('saved_opportunities');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('opportunity_skills');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('company_members');
        Schema::dropIfExists('companies');

        if (Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('user_type');
            });
        }
    }
};
