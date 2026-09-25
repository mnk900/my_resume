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
            DB::statement('ALTER TABLE candidate_notes MODIFY user_id BIGINT UNSIGNED NULL');
        }

        Schema::table('candidate_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('candidate_notes', 'job_application_id')) {
                $table->foreignId('job_application_id')->nullable()->after('company_id')->constrained('job_applications')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_notes', function (Blueprint $table) {
            if (Schema::hasColumn('candidate_notes', 'job_application_id')) {
                $table->dropForeign(['job_application_id']);
                $table->dropColumn('job_application_id');
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE candidate_notes MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
