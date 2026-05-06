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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_id');
            $table->unsignedInteger('row_number');
            $table->json('data');
            $table->json('errores')->nullable();
            $table->enum('status', ['pending', 'error', 'processed'])->default('pending');
            $table->boolean('is_valid')->default(true);
            $table->string('folio_real')->nullable();
            $table->timestamps();
            $table->index('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
