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
        Schema::create('sentencias', function (Blueprint $table) {
            $table->id();
            $table->string('acto_contenido')->nullable();
            $table->string('estado')->nullable();
            $table->string('servicio')->nullable();
            $table->text('descripcion')->nullable();
            $table->foreignId('movimiento_registral_id')->constrained()->onDelete('cascade');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentencias');
    }
};
