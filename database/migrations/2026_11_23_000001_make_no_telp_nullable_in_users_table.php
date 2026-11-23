<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bersihkan data dummy '-' yang tersimpan dari percobaan Google login sebelumnya
        DB::table('users')->where('noTelp', '-')->update(['noTelp' => null]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('noTelp', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('noTelp', 20)->nullable(false)->change();
        });
    }
};
