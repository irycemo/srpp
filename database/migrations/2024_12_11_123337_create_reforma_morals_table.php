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
        Schema::create('reforma_morals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimiento_registral_id')->constrained()->onDelete('cascade');
            $table->string('acto_contenido');
            $table->date('fecha_inscripcion');
            $table->date('fecha_protocolizacion');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reforma_morals');
    }
};
