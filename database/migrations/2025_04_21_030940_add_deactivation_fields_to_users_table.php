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
            // Primeiro adiciona as colunas
            $table->unsignedBigInteger('deactivated_by')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->text('deactivation_reason')->nullable();
            $table->boolean('is_active')->default(true);

            // Depois adiciona a chave estrangeira
            $table->foreign('deactivated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['deactivated_by']);
            $table->dropColumn(['deactivated_by', 'deactivated_at', 'deactivation_reason', 'is_active']);
        });
    }
};
