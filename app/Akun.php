<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'kdjenis', 'jenis', 'kdakun', 'nmakun', 'kdsubakun', 'nmsubakun'
    ];
    static function getNamaAkun($kdjenis)
    {
        return Akun::select('kdakun', 'nmakun')->distinct()->where('kdjenis', $kdjenis)->get();
    }
    static function getKdAkun($kdakun)
    {
        $count = Akun::where('kdakun', $kdakun)->count();
        return str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
    static function getAkun($kdsubakun)
    {
        return Akun::selectRaw('kdsubakun,nmsubakun')->where('kdsubakun', $kdsubakun)->first();
    }
}
