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
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id', 20)->primary();
            $table->string('kabupatenId', 20)->index();
            $table->string('namaKecamatan', 255);
            $table->timestamps();

            $table->foreign('kabupatenId')->references('id')->on('kabupatens')->cascadeOnDelete();
        });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('kecamatans');
        }
    };
