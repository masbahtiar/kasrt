<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setapp extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'id', 'periode', 'nominal_jimpitan', 'aktif'
    ];
    //
    public function sekolahs()
    {
        return $this->hasMany(RuangSekolah::class, 'id_nmruang');
    }
    public static function getByTahun($tahun = null)
    {
        if ($tahun) {
            return TahunAjaran::where('aktif', 'yes')->where('tahun', $tahun)->get()->first();
        }
        return TahunAjaran::where('aktif', 'yes')->get()->first();
    }
}
