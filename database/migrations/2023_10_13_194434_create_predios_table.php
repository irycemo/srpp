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
        Schema::create('predios', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->foreignId('folio_real')->references('id')->on('folio_reals');
            $table->foreignId('escritura_id')->nullable()->references('id')->on('escrituras');

            $table->string('curt')->nullable();
            /* Valores y superficies */
            $table->unsignedDecimal('superficie_terreno', 18,2)->nullable();
            $table->unsignedDecimal('superficie_construccion', 18,2)->nullable();
            $table->unsignedDecimal('superficie_judicial', 18,2)->nullable();
            $table->unsignedDecimal('superficie_notarial', 18,2)->nullable();
            $table->unsignedDecimal('area_comun_terreno', 10, 2)->nullable();
            $table->unsignedDecimal('area_comun_construccion', 10, 2)->nullable();
            $table->unsignedDecimal('valor_terreno_comun', 10, 2)->nullable();
            $table->unsignedDecimal('valor_construccion_comun', 10, 2)->nullable();
            $table->unsignedDecimal('valor_total_terreno', 18,2)->nullable();
            $table->unsignedDecimal('valor_total_construccion', 18,2)->nullable();
            $table->unsignedDecimal('valor_catastral', 18,2)->nullable();
            $table->string('divisa')->nullable();
            /* Ubicaicón */
            $table->string('tipo_vialidad')->nullable();
            $table->string('tipo_asentamiento')->nullable();
            $table->string('nombre_vialidad')->nullable();
            $table->string('nombre_asentamiento')->nullable();
            $table->string('numero_exterior')->nullable();
            $table->string('numero_exterior_2')->nullable();
            $table->string('numero_adicional')->nullable();
            $table->string('numero_adicional_2')->nullable();
            $table->string('numero_interior')->nullable();
            $table->string('lote')->nullable();
            $table->string('manzana')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('lote_fraccionador')->nullable();
            $table->string('manzana_fraccionador')->nullable();
            $table->string('etapa_fraccionador')->nullable();
            $table->string('nombre_edificio')->nullable();
            $table->string('clave_edificio')->nullable();
            $table->string('departamento_edificio')->nullable();
            $table->text('entre_vialidades')->nullable();
            $table->text('nombre_predio')->nullable();
            $table->string('estado')->nullable();
            $table->string('municipio')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('localidad')->nullable();
            $table->string('poblado')->nullable();
            $table->string('ejido')->nullable();
            $table->string('parcela')->nullable();
            $table->string('solar')->nullable();
            $table->string('uso_suelo')->nullable();
            /* Clave catastral */
            $table->unsignedInteger('cc_estado')->nullable();
            $table->unsignedInteger('cc_region_catastral')->nullable();
            $table->unsignedInteger('cc_municipio')->nullable();
            $table->unsignedInteger('cc_zona_catastral')->nullable();
            $table->unsignedInteger('cc_sector')->nullable();
            $table->unsignedInteger('cc_manzana')->nullable();
            $table->unsignedInteger('cc_predio')->nullable();
            $table->unsignedInteger('cc_edificio')->nullable();
            $table->unsignedInteger('cc_departamento')->nullable();
            /* Cuenta predial */
            $table->unsignedInteger('cp_localidad')->nullable();
            $table->unsignedInteger('cp_oficina')->nullable();
            $table->unsignedInteger('cp_tipo_predio')->nullable();
            $table->unsignedInteger('cp_registro')->nullable();

            $table->text('descripcion')->nullable();
            $table->foreignId('creado_por')->nullable()->references('id')->on('users');
            $table->foreignId('actualizado_por')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predios');
    }
};
