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
            $cols = [
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
            ];
            foreach ($cols as $col) {
                if (!Schema::hasColumn('portfolios', $col)) {
                    $table->boolean($col)->default(true);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $cols = [
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
            ];
            $colsToDrop = [];
            foreach ($cols as $col) {
                if (Schema::hasColumn('portfolios', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
