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
        if (Schema::hasTable('opportunity_skills')) {
            Schema::table('opportunity_skills', function (Blueprint $table) {
                $table->text('skill_name')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('opportunity_skills')) {
            Schema::table('opportunity_skills', function (Blueprint $table) {
                $table->string('skill_name')->change();
            });
        }
    }
};
