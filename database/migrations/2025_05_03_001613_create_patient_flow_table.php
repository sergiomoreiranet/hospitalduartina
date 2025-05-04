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
        Schema::create('patient_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Profissional responsável
            $table->enum('status', ['aguardando', 'em_atendimento', 'concluido', 'cancelado'])->default('aguardando');
            $table->timestamp('check_in')->nullable(); // Horário de entrada no setor
            $table->timestamp('check_out')->nullable(); // Horário de saída do setor
            $table->foreignId('next_sector_id')->nullable()->constrained('sectors')->nullOnDelete(); // Próximo setor
            $table->text('observations')->nullable(); // Observações do atendimento
            $table->text('conclusion')->nullable(); // Conclusão do atendimento
            $table->boolean('is_priority')->default(false); // Se é caso prioritário
            $table->integer('queue_position')->nullable(); // Posição na fila
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_flows');
    }
};
