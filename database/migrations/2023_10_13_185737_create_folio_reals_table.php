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
        Schema::create('folio_reals', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->unsignedInteger('folio')->unique();
            $table->foreignId('antecedente')->nullable()->references('id')->on('folio_reals');
            $table->string('tomo_antecedente')->nullable();
            $table->boolean('tomo_antecedente_bis')->nullable();
            $table->string('registro_antecedente')->nullable();
            $table->boolean('registro_antecedente_bis')->nullable();
            $table->string('numero_propiedad_antecedente')->nullable();
            $table->string('distrito_antecedente')->nullable();
            $table->string('seccion_antecedente')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('autoridad_cargo')->nullable();
            $table->string('autoridad_nombre')->nullable();
            $table->string('autoridad_numero')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_inscripcion')->nullable();
            $table->string('procedencia')->nullable();
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
        Schema::dropIfExists('folio_reals');
    }
};
