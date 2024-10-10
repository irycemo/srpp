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

        Schema::table('predios', function (Blueprint $table) {
            $table->unsignedDecimal('superficie_terreno', 15,2)->nullable()->change();
            $table->unsignedDecimal('superficie_construccion', 15,2)->nullable()->change();
            $table->unsignedDecimal('superficie_judicial', 15,2)->nullable()->change();
            $table->unsignedDecimal('superficie_notarial', 15,2)->nullable()->change();
            $table->unsignedDecimal('area_comun_terreno', 15,2)->nullable()->change();
            $table->unsignedDecimal('area_comun_construccion', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_terreno_comun', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_construccion_comun', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_total_terreno', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_total_construccion', 15,2)->nullable()->change();
            $table->unsignedDecimal('monto_transaccion', 15,2)->nullable()->change();
        });

        Schema::table('propiedads', function (Blueprint $table) {
            $table->unsignedDecimal('superficie_terreno', 15,2)->nullable()->change();
            $table->unsignedDecimal('superficie_construccion', 15,2)->nullable()->change();
            $table->unsignedDecimal('superficie_judicial', 15,2)->nullable()->change();
            $table->unsignedDecimal('superficie_notarial', 15,2)->nullable()->change();
            $table->unsignedDecimal('area_comun_terreno', 15,2)->nullable()->change();
            $table->unsignedDecimal('area_comun_construccion', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_terreno_comun', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_construccion_comun', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_total_terreno', 15,2)->nullable()->change();
            $table->unsignedDecimal('valor_total_construccion', 15,2)->nullable()->change();
            $table->unsignedDecimal('monto_transaccion', 15,2)->nullable()->change();
        });

        Schema::table('actors', function (Blueprint $table) {
            $table->unsignedDecimal('porcentaje_nuda', 15,2)->nullable()->change();
            $table->unsignedDecimal('porcentaje_usufructo', 15,2)->nullable()->change();
            $table->unsignedDecimal('porcentaje_propiedad', 15,2)->nullable()->change();
        });

        Schema::table('colindancias', function (Blueprint $table) {
            $table->unsignedDecimal('longitud', 15,2)->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
