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
        $tables = [
            'skills',
            'projects',
            'experiences',
            'education',
            'certifications',
            'trainings',
            'services',
            'achievements',
            'contributions',
            'testimonials',
            'media',
            'publications',
            'portfolio_sections',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'is_active')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->boolean('is_active')->default(true)->after('id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'skills',
            'projects',
            'experiences',
            'education',
            'certifications',
            'trainings',
            'services',
            'achievements',
            'contributions',
            'testimonials',
            'media',
            'publications',
            'portfolio_sections',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'is_active')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('is_active');
                });
            }
        }
    }
};
