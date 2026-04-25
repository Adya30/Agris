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
        Schema::create('produks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->foreignUlid('kategoriId')->constrained('kategori_produks')->restrictOnDelete();
            $table->string('fotoProduk', 255)->nullable();
            $table->string('namaProduk', 150);
            $table->decimal('stok', 15, 2)->default(0);
            $table->decimal('harga', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('namaProduk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
