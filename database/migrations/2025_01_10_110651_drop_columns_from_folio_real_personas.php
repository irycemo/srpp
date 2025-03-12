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

            if (Schema::hasColumn('folio_real_personas', 'notaria')) {
                $table->dropColumn('notaria');
            }

            if (Schema::hasColumn('folio_real_personas', 'nombre_notario')) {
                $table->dropColumn('nombre_notario');
            }

            if (Schema::hasColumn('folio_real_personas', 'numero_escritura')) {
                $table->dropColumn('numero_escritura');
            }

            if (Schema::hasColumn('folio_real_personas', 'numero_hojas')) {
                $table->dropColumn('numero_hojas');
            }

            $table->foreignId('escritura_id')->nullable()->after('folio')->references('id')->on('escrituras');

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
