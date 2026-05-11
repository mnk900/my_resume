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
            $table->boolean('show_email')->default(true)->after('show_contact_info');
            $table->boolean('show_phone')->default(true)->after('show_email');
            $table->boolean('show_linkedin')->default(true)->after('show_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['show_email', 'show_phone', 'show_linkedin']);
        });
    }
};
