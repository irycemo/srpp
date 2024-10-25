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
        Schema::table('antecedentes', function (Blueprint $table) {
            $table->foreignId('folio_real_antecedente')->nullable()->references('id')->on('folio_reals')->after('folio_real');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antecedentes', function (Blueprint $table) {
            //
        });
    }
};
