<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE job_applications MODIFY user_id BIGINT UNSIGNED NULL');
        }

        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'is_public_applicant')) {
                $table->boolean('is_public_applicant')->default(false)->after('user_id');
            }
            if (!Schema::hasColumn('job_applications', 'applicant_name')) {
                $table->string('applicant_name')->nullable()->after('is_public_applicant');
            }
            if (!Schema::hasColumn('job_applications', 'applicant_email')) {
                $table->string('applicant_email')->nullable()->after('applicant_name');
            }
            if (!Schema::hasColumn('job_applications', 'applicant_phone')) {
                $table->string('applicant_phone')->nullable()->after('applicant_email');
            }
            if (!Schema::hasColumn('job_applications', 'is_currently_employed')) {
                $table->boolean('is_currently_employed')->default(false)->after('applicant_phone');
            }
            if (!Schema::hasColumn('job_applications', 'current_designation')) {
                $table->string('current_designation')->nullable()->after('is_currently_employed');
            }
            if (!Schema::hasColumn('job_applications', 'current_organization_name')) {
                $table->string('current_organization_name')->nullable()->after('current_designation');
            }
            if (!Schema::hasColumn('job_applications', 'current_organization_address')) {
                $table->string('current_organization_address')->nullable()->after('current_organization_name');
            }
            if (!Schema::hasColumn('job_applications', 'cover_letter_path')) {
                $table->string('cover_letter_path')->nullable()->after('cover_letter');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'is_public_applicant',
                'applicant_name',
                'applicant_email',
                'applicant_phone',
                'is_currently_employed',
                'current_designation',
                'current_organization_name',
                'current_organization_address',
                'cover_letter_path',
            ]);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE job_applications MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
