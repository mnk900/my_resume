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
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
                $table->string('type'); // 'tv' or 'oped'
                $table->string('title');
                $table->string('channel_platform')->nullable();
                $table->string('newspaper_name')->nullable();
                $table->date('date');
                $table->string('link');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
