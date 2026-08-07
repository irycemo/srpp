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
        Schema::table('movimiento_registrals', function (Blueprint $table) {
            $table->timestamp('fecha_elaboracion')->index()->nullable()->after('actualizado_por');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimiento_registrals', function (Blueprint $table) {
            //
        });
    }
};
