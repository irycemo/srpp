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
        Schema::create('certificacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimiento_registral_id')->constrained();
            $table->string('servicio');
            $table->unsignedInteger('numero_paginas')->nullable();
            $table->unsignedInteger('folio_carpeta_copias')->unique()->nullable();
            $table->timestamp('firma')->nullable();
            $table->timestamp('finalizado_en')->nullable();
            $table->timestamp('reimpreso_en')->nullable();
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificacions');
    }
};
