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
        Schema::table('patients', function (Blueprint $table) {
            $table->boolean('is_hospitalized')->default(false);
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->nullOnDelete();
            $table->date('hospitalization_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->string('bed_number')->nullable();
            $table->text('hospitalization_reason')->nullable();
            $table->text('medical_team')->nullable();
            $table->string('status')->default('aguardando')->comment('aguardando, internado, alta, transferido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn([
                'is_hospitalized',
                'sector_id',
                'hospitalization_date',
                'discharge_date',
                'bed_number',
                'hospitalization_reason',
                'medical_team',
                'status'
            ]);
        });
    }
};
