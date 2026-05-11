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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'show_email')) {
                $table->dropColumn('show_email');
            }
            if (Schema::hasColumn('users', 'show_phone')) {
                $table->dropColumn('show_phone');
            }
            if (Schema::hasColumn('users', 'show_linkedin')) {
                $table->dropColumn('show_linkedin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'show_email')) {
                $table->boolean('show_email')->default(true)->after('email');
            }
            if (! Schema::hasColumn('users', 'show_phone')) {
                $table->boolean('show_phone')->default(true)->after('show_email');
            }
            if (! Schema::hasColumn('users', 'show_linkedin')) {
                $table->boolean('show_linkedin')->default(true)->after('show_phone');
            }
        });
    }
};
