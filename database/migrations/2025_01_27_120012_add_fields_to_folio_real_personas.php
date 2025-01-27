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
            $table->string('registro_antecedente')->nullable()->after('folio');
            $table->string('tomo_antecedente')->nullable()->after('folio');
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
