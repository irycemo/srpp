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
        Schema::create('codigo_postals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('codigo');
            $table->string('tipo_asentamiento');
            $table->string('nombre_asentamiento');
            $table->string('municipio');
            $table->string('ciudad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_postals');
    }
};
