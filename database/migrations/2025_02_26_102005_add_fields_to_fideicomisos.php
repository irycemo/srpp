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
        Schema::table('fideicomisos', function (Blueprint $table) {
            $table->string('tipo')->nullable();
            $table->text('objeto')->nullable();
            $table->timestamp('fecha_inscripcion')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->foreignId('movimiento_registral_id')->constrained()->onDelete('cascade');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fideicomisos', function (Blueprint $table) {
            //
        });
    }
};
