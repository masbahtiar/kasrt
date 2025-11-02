<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->string('kepse', 250);
            $table->string('nip_kepse', 100);
            $table->string('telp', 50);
            $table->string('komite', 250);
            $table->integer('luas_tanah');
            $table->integer('luas_bangunan');
            $table->integer('jml_siswa');
            $table->integer('jml_rombel');
            $table->integer('koleksi_buku');
            $table->integer('jml_kelas');
            $table->integer('jml_toilet');
            $table->integer('jml_laki_laki');
            $table->integer('jml_perempuan');
            $table->integer('sekolah_id');
            $table->integer('tahun_ajaran_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profils');
    }
}
