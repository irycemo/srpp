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
        Schema::create('movimiento_registrals', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->unsignedInteger('folio')->nullable();
            $table->foreignId('folio_real')->nullable()->references('id')->on('folio_reals');
            /* Trámite */
            $table->unsignedInteger('año')->nullable();
            $table->unsignedInteger('tramite')->nullable();
            $table->unsignedDecimal("monto", 18, 2)->nullable();
            $table->timestamp('fecha_prelacion')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('tipo_servicio')->nullable();
            $table->string('solicitante')->nullable();
            /* Antecedente */
            $table->string("tomo")->nullable();
            $table->boolean("tomo_bis")->nullable();
            $table->string("registro")->nullable();
            $table->boolean("registro_bis")->nullable();
            $table->string('seccion');
            $table->string('distrito');
            /* Documento de entrada */
            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento')->nullable();
            $table->unsignedInteger('numero_propiedad')->nullable()->comment("Número de propiedad dentro de la escritura");
            $table->string('autoridad_cargo')->nullable();
            $table->string('autoridad_nombre')->nullable();
            $table->string('autoridad_numero')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_inscripcion')->nullable();
            $table->string('procedencia')->nullable();
            /* Otros */
            $table->string('numero_oficio')->nullable();
            /* Usuarios */
            $table->foreignId('usuario_asignado')->nullable()->references('id')->on('users');
            $table->foreignId('usuario_supervisor')->nullable()->references('id')->on('users');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_registrals');
    }
};
