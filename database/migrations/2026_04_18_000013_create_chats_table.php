<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->foreignUlid('id_pengirim')->constrained('users')->cascadeOnDelete();
            $table->string('id_penerima');
            $table->text('pesan')->nullable();
            $table->longText('foto_chat')->nullable();
            $table->enum('status', ['terkirim','dibaca'])->default('terkirim');
            $table->dateTime('waktu_chat');
            $table->timestamps();

            $table->index(['id_pengirim', 'id_penerima']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
