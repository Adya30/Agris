<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_produks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('jenisKategori');
            $table->decimal('karung', 15, 2);
            $table->string('mutu');
            $table->timestamps();
            $table->unique(['jenisKategori', 'mutu', 'karung']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_produks');
    }
};
