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
        if (Schema::hasTable('opportunities')) {
            Schema::table('opportunities', function (Blueprint $table) {
                if (!Schema::hasColumn('opportunities', 'compensation_type')) {
                    $table->string('compensation_type')->default('salary')->after('employment_type'); // salary, revenue_share, hybrid
                }
                if (!Schema::hasColumn('opportunities', 'revenue_share_min')) {
                    $table->decimal('revenue_share_min', 5, 2)->nullable()->after('compensation_type');
                }
                if (!Schema::hasColumn('opportunities', 'revenue_share_max')) {
                    $table->decimal('revenue_share_max', 5, 2)->nullable()->after('revenue_share_min');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('opportunities')) {
            Schema::table('opportunities', function (Blueprint $table) {
                $columns = ['compensation_type', 'revenue_share_min', 'revenue_share_max'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('opportunities', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
