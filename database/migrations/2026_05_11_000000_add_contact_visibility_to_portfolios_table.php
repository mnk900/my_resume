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
            if (!Schema::hasColumn('portfolios', 'show_email')) {
                $table->boolean('show_email')->default(true)->after('show_contact_info');
            }
            if (!Schema::hasColumn('portfolios', 'show_phone')) {
                $table->boolean('show_phone')->default(true)->after('show_email');
            }
            if (!Schema::hasColumn('portfolios', 'show_linkedin')) {
                $table->boolean('show_linkedin')->default(true)->after('show_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('portfolios', 'show_email')) $cols[] = 'show_email';
            if (Schema::hasColumn('portfolios', 'show_phone')) $cols[] = 'show_phone';
            if (Schema::hasColumn('portfolios', 'show_linkedin')) $cols[] = 'show_linkedin';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
