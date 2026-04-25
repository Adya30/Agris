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
        Schema::create('chats', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->foreignUlid('id_pengirim')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('id_penerima')->constrained('users')->cascadeOnDelete();
            $table->text('pesan')->nullable();
            $table->string('foto_chat', 255)->nullable();
            $table->enum('status', ['terkirim','dibaca'])->default('terkirim');
            $table->dateTime('waktu_chat')->useCurrent();
            $table->timestamps();
            $table->index(['id_pengirim', 'id_penerima']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
