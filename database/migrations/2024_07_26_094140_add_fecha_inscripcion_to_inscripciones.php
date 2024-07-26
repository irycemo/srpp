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
        Schema::table('varios', function (Blueprint $table) {
            $table->date('fecha_inscripcion')->nullable()->after('descripcion');
        });

        Schema::table('sentencias', function (Blueprint $table) {
            $table->date('fecha_inscripcion')->nullable()->after('descripcion');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            //
        });
    }
};
