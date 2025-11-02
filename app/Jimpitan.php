<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jimpitan extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'no_ref', 'tanggal_jimpitan', 'ket_jimpitan', 'bulan', 'tahun', 'periode', 'nominal', 'jumlah_peserta', 'sub_total',
        'upah', 'jumlah_terima', 'grup', 'user_id', 'image_url',
    ];
}
