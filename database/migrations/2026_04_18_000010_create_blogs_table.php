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
        Schema::create('blogs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->ulid('id')->primary();
            $table->foreignUlid('userId')->constrained('users')->restrictOnDelete();
            $table->string('judulBlog', 200);
            $table->text('isiBlog');
            $table->string('fotoBlog', 255)->nullable();
            $table->date('tanggalBlog')->nullable();
            $table->timestamps();
            $table->index('judulBlog');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};