<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('pesananId')->constrained('pesanans')->cascadeOnDelete();
            $table->foreignUlid('produkId')->constrained('produks')->restrictOnDelete();
            $table->integer('jumlahPesanan');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
