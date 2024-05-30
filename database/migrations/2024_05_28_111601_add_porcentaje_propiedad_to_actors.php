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
        Schema::table('actors', function (Blueprint $table) {
            $table->unsignedDecimal('porcentaje_propiedad', 15,2)->nullable()->after('porcentaje_nuda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actors', function (Blueprint $table) {
            //
        });
    }
};
