<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('pesananId')->constrained('pesanans')->cascadeOnDelete();
            $table->foreignUlid('detailPesananId')->constrained('detail_pesanans')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->decimal('nominal', 15, 2);
            $table->text('alasan');
            $table->string('foto_bukti');
            $table->string('status')->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
