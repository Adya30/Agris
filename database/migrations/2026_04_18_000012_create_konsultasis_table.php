<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('userId')->constrained('users')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->text('jawaban')->nullable();
            $table->string('statusKonsultasi')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
};
