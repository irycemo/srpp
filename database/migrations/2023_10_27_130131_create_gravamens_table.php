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
            $table->string('servicio')->nullable();
            $table->string('estado')->nullable();
            $table->foreignId('movimiento_registral_id')->constrained()->onDelete('cascade');
            $table->string('tipo')->nullable();
            $table->decimal('valor_gravamen', 15,2)->nullable();
            $table->string('divisa')->nullable();
            $table->date('fecha_inscripcion')->nullable();
            $table->text('observaciones')->nullable();
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
