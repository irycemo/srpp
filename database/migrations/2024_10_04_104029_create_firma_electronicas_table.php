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
        Schema::create('firma_electronicas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('folio_real')->nullable()->references('id')->on('folio_reals');
            $table->foreignId('movimiento_registral_id')->nullable()->references('id')->on('movimiento_registrals');
            $table->text('cadena_original');
            $table->text('cadena_encriptada')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firma_electronicas');
    }
};
