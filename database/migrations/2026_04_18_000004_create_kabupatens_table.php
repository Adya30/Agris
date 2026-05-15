<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('provinsiId', 20)->index();
            $table->string('namaKabupaten', 255);
            $table->timestamps();
            $table->foreign('provinsiId')->references('id')->on('provinsis')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kabupatens');
    }
};
