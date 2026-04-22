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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->foreignUlid('userId')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pesanan');
            $table->text('alamat_pengiriman');
            $table->foreignUlid('desaId')->nullable()->constrained('desas')->nullOnDelete();
            $table->enum('status_pesanan', ['dikemas', 'diproses', 'dikirim', 'selesai'])->default('dikemas');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
