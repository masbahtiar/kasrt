<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJimpitansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jimpitans', function (Blueprint $table) {
            $table->id();
            $table->timestamp('tanggal_jimpitan')->nullable();
            $table->string('no_ref', 100);
            $table->text('ket_jimpitan');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->enum('periode', ['I', 'II', 'III', 'IV']);
            $table->integer('nominal');
            $table->integer('jumlah_peserta');
            $table->integer('sub_total');
            $table->integer('upah');
            $table->integer('jumlah_terima');
            $table->string('grup', 100);
            $table->integer('user_id');
            $table->string('image_url', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jimpitans');
    }
}
