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
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_sector_admin')->default(false);
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn(['is_admin', 'is_sector_admin', 'sector_id']);
        });
    }
};
