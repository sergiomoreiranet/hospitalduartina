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
        Schema::table('sectors', function (Blueprint $table) {
            $table->foreignId('deactivated_by')->nullable()->constrained('users');
            $table->timestamp('deactivated_at')->nullable();
            $table->text('deactivation_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->dropForeign(['deactivated_by']);
            $table->dropColumn(['deactivated_by', 'deactivated_at', 'deactivation_reason']);
        });
    }
};
