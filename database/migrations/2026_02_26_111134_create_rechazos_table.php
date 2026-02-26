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
        Schema::create('rechazos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimiento_registral_id')->constrained()->onDelete('cascade');
            $table->string('fundamento');
            $table->string('observaciones');
            $table->foreignId('creado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rechazos');
    }
};
