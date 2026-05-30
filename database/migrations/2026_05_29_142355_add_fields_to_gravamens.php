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
        Schema::table('gravamens', function (Blueprint $table) {
            $table->foreignId('asociado_a')->nullable()->after('movimiento_registral_id')->references('id')->on('gravamens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gravamens', function (Blueprint $table) {
            //
        });
    }
};
