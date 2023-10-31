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
        Schema::create('gravamens', function (Blueprint $table) {
            $table->id();
            $table->string('acto_contenido')->nullable();
            $table->string('servicio');
            $table->foreignId('escritura_id')->nullable()->constrained();
            $table->foreignId('movimiento_registral_id')->constrained();
            $table->string('tomo');
            $table->string('registro');
            $table->string('distrito');
            $table->string('tipo');
            $table->string('tipo_deudor');
            $table->string('nombre_deudor');
            $table->string('acredor_nombre');
            $table->decimal('valor_gravamen', 15,2);
            $table->string('divisa');
            $table->date('fecha_inscripcion');
            $table->text('observaciones');
            $table->string('estado');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gravamens');
    }
};
