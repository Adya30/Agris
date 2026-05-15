<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('userId')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pesanan');
            $table->text('alamat_pengiriman');
            $table->foreignUlid('desaId')->nullable()->constrained('desas')->nullOnDelete();
            $table->string('status_pesanan')->default('dikemas');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
