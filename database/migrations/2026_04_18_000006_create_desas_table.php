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
    Schema::create('desas', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->string('id', 20)->primary();
        $table->string('kecamatanId', 20)->index();
        $table->string('namaDesa', 255);
        $table->string('kodePos', 10)->nullable();
        $table->timestamps();

        $table->foreign('kecamatanId')->references('id')->on('kecamatans')->cascadeOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desas');
    }
};
