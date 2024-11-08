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
        Schema::table('sentencias', function (Blueprint $table) {
            $table->string('tomo')->nullable()->after('servicio');
            $table->string('registro')->nullable()->after('servicio');
            $table->string('expediente')->nullable()->after('servicio');
            $table->unsignedInteger('hojas')->nullable()->after('servicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sentencias', function (Blueprint $table) {
            //
        });
    }
};

