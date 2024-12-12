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
        Schema::table('folio_real_personas', function (Blueprint $table) {
            $table->renameColumn('`descripcion`', 'domicilio');
            $table->renameColumn('`fecha_celebracion`', 'fecha_constitucion');
            $table->string('tipo')->nullable()->after('numero_hojas');
            $table->unsignedInteger('capital')->nullable()->after('numero_hojas');
            $table->unsignedInteger('duracion')->nullable()->after('numero_hojas');
            $table->date('fecha_disolucion')->nullable()->after('numero_hojas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folio_real_personas', function (Blueprint $table) {
            //
        });
    }
};
