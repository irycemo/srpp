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
            $table->decimal('valor_gravamen_2', 15,2)->nullable();
            $table->string('divisa_2')->nullable();
            $table->decimal('valor_gravamen_3', 15,2)->nullable();
            $table->string('divisa_3')->nullable();
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
