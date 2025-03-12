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
        Schema::create('asignacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificador')->nullable()->references('id')->on('users');
            $table->foreignId('certificado_gravamen')->nullable()->references('id')->on('users');
            $table->foreignId('certificado_propiedad')->nullable()->references('id')->on('users');
            $table->foreignId('propiedad')->nullable()->references('id')->on('users');
            $table->foreignId('gravamen')->nullable()->references('id')->on('users');
            $table->foreignId('cancelacion')->nullable()->references('id')->on('users');
            $table->foreignId('sentencia')->nullable()->references('id')->on('users');
            $table->foreignId('varios')->nullable()->references('id')->on('users');
            $table->foreignId('certificador_uruapan')->nullable()->references('id')->on('users');
            $table->foreignId('certificado_gravamen_uruapan')->nullable()->references('id')->on('users');
            $table->foreignId('certificado_propiedad_uruapan')->nullable()->references('id')->on('users');
            $table->foreignId('propiedad_uruapan')->nullable()->references('id')->on('users');
            $table->foreignId('gravamen_uruapan')->nullable()->references('id')->on('users');
            $table->foreignId('cancelacion_uruapan')->nullable()->references('id')->on('users');
            $table->foreignId('sentencia_uruapan')->nullable()->references('id')->on('users');
            $table->foreignId('varios_uruapan')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacions');
    }
};
