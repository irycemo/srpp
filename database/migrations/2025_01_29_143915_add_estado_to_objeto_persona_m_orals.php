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
        Schema::table('objeto_persona_m_orals', function (Blueprint $table) {
            $table->string('estado')->nullable()->after('folio_real_persona');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objeto_persona_m_orals', function (Blueprint $table) {
            //
        });
    }
};
