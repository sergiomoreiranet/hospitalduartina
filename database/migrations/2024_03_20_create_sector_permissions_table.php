<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sector_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained()->onDelete('cascade');
            $table->string('module_code'); // Ex: 1.1, 1.2, 7.7, etc
            $table->string('module_name'); // Nome do módulo
            $table->boolean('can_view')->default(true);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sector_permissions');
    }
}; 