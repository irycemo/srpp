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
        Schema::table('certificacions', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_certificado')->nullable()->after('servicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificaciones', function (Blueprint $table) {
            //
        });
    }
};
