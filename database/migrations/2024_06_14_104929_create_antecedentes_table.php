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
        Schema::create('antecedentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folio_real')->nullable()->references('id')->on('folio_reals');
            $table->string('tomo_antecedente')->nullable();
            $table->boolean('tomo_antecedente_bis')->nullable();
            $table->string('registro_antecedente')->nullable();
            $table->boolean('registro_antecedente_bis')->nullable();
            $table->string('numero_propiedad_antecedente')->nullable();
            $table->string('distrito_antecedente')->nullable();
            $table->string('seccion_antecedente')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes');
    }
};
