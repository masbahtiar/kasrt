<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'no_ref', 'jenis_transaksi_id', 'ket_jurnal', 'debet', 'kredit', 'grup', 'user_id', 'image_url', 'kdsubakun', 'tanggal_jurnal'
    ];
    public function jenis_transaksi()
    {
        return $this->belongsTo(JenisTransaksi::class, 'jenis_transaksi_id');
    }
}
