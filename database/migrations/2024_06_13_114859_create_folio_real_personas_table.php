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
        Schema::create('folio_real_personas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('folio')->unique();
            $table->string('denominacion');
            $table->date('fecha_celebracion');
            $table->dateTime('fecha_inscripcion');
            $table->unsignedInteger('notaria');
            $table->string('nombre_notario');
            $table->string('numero_escritura');
            $table->unsignedInteger('numero_hojas');
            $table->text('descripcion');
            $table->text('observaciones');
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
        Schema::dropIfExists('folio_real_personas');
    }
};
