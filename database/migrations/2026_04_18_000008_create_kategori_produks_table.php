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
        Schema::create('kategori_produks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->enum('jenisKategori', ['padi', 'jagung']);
            $table->enum('mutu', ['A', 'B', 'C']);
            $table->timestamps();
            $table->unique(['jenisKategori', 'mutu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_produks');
    }
};
