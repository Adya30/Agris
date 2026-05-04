<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kemitraans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->foreignUlid('userId')->constrained('users')->cascadeOnDelete();
            $table->date('tanggalPengajuan');
            $table->enum('statusPengajuan', [ 'diproses', 'Menunggu Upload MOU', 'Menunggu Verifikasi MOU', 'Aktif', 'Ditolak'
            ])->default('diproses');
            $table->longText('fileKemitraan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kemitraans');
    }
};
