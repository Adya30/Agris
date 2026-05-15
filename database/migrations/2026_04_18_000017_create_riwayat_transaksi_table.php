<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_transaksis', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('userId')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('pembayaranId')->constrained('pembayarans')->cascadeOnDelete();
            $table->string('kategoriRiwayat');
            $table->dateTime('tanggalRiwayat')->useCurrent();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->index('userId');
            $table->index('pembayaranId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_transaksis');
    }
};
