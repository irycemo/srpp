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
        Schema::create('actors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained();
            $table->foreignId('predio_id')->constrained()->onDelete('cascade');
            $table->string('tipo_actor')->nullable();
            $table->string('tipo_propietario')->nullable();
            $table->unsignedDecimal('porcentaje_nuda', 15,2)->nullable();
            $table->unsignedDecimal('porcentaje_usufructo', 15,2)->nullable();
            $table->foreignId('representado_por')->nullable()->references('id')->on('actors')->nullOnDelete();
            $table->foreignId('creado_por')->nullable()->references('id')->on('users');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actors');
    }
};
