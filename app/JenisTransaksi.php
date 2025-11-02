<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'nama_jtransaksi', 'akun_debet', 'akun_kredit', 'keterangan', 'nama_alias'
    ];
    public function akundebet()
    {
        return Akun::getAkun($this->akun_debet);
    }
    public function akunkredit()
    {
        return Akun::getAkun($this->akun_kredit);
    }
}
