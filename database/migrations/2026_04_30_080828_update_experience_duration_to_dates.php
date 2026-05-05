<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn('duration');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable(); // Null means "Present"
        });
        
        Schema::table('education', function (Blueprint $table) {
            $table->dropColumn('duration');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('duration')->nullable();
            $table->dropColumn(['start_date', 'end_date']);
        });

        Schema::table('education', function (Blueprint $table) {
            $table->string('duration')->nullable();
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
