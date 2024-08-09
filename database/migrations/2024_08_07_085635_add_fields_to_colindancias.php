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
        Schema::table('colindancias', function (Blueprint $table) {
            $table->string('viento')->nullable()->change();
            $table->unsignedDecimal('longitud', 15, 2)->nullable()->change();
            $table->text('descripcion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colindancias', function (Blueprint $table) {
            //
        });
    }
};
