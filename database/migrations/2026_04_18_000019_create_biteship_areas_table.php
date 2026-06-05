<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biteship_areas', function (Blueprint $table) {
            $table->id();
            $table->string('desaId', 20)->unique();
            $table->string('biteship_area_id', 100);
            $table->string('biteship_name');
            $table->timestamps();
            $table->foreign('desaId')->references('id')->on('desas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biteship_areas');
    }
};
