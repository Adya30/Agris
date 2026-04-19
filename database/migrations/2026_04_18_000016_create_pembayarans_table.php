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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->foreignUlid('pesananId')->constrained('pesanans')->cascadeOnDelete();
            $table->string('snapToken', 255)->nullable();
            $table->string('transactionId')->nullable();
            $table->enum('statusPembayaran', ['pending','settlement', 'expire', 'cancel', 'deny', 'refund'])->default('pending');
            $table->string('paymentType')->nullable();
            $table->decimal('totalPembayaran', 15, 2);
            $table->dateTime('waktuDibayar')->nullable();
            $table->decimal('jumlahRefund', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
