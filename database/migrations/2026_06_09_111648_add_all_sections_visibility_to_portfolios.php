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
        Schema::table('portfolios', function (Blueprint $table) {
            $table->boolean('show_skills')->default(true);
            $table->boolean('show_projects')->default(true);
            $table->boolean('show_experience')->default(true);
            $table->boolean('show_education')->default(true);
            $table->boolean('show_services')->default(true);
            $table->boolean('show_certifications')->default(true);
            $table->boolean('show_trainings')->default(true);
            $table->boolean('show_achievements')->default(true);
            $table->boolean('show_contributions')->default(true);
            $table->boolean('show_testimonials')->default(true);
            $table->boolean('show_media')->default(true);
            $table->boolean('show_publications')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn([
                'show_skills',
                'show_projects',
                'show_experience',
                'show_education',
                'show_services',
                'show_certifications',
                'show_trainings',
                'show_achievements',
                'show_contributions',
                'show_testimonials',
                'show_media',
                'show_publications'
            ]);
        });
    }
};
