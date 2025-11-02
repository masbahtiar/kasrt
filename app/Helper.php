<?php

/**
 * change plain number to formatted currency
 *
 * @param $number
 * @param $currency
 */

use App\Jimpitan;
use App\Kelayakan;
use App\Setting;
use Illuminate\Support\Facades\Auth;

function getKerusakan($nilai)
{
    $hasil = "";
    $klk = Kelayakan::where('kelayakans.minimal', '<=', $nilai)
        ->where('kelayakans.maksimal', '>=', $nilai)
        ->first();
    if ($klk) {
        $hasil = $klk->keterangan;
    }
    return $hasil;
}

function getSetting($nilai)
{
    $hasil = 0;
    $klk = Setting::find($nilai);
    if ($klk) {
        $hasil = $klk->nilai;
    }
    return $hasil;
}
function getSisaPeserta($tahun, $bulan, $periode)
{
    $prev_peserta = getSetting('jumlah_peserta');
    $data = Jimpitan::selectRaw('sum(jumlah_peserta) as jumlah')
        ->where('tahun', $tahun)
        ->where('bulan', $bulan)
        ->where('periode', $periode)->first();
    return $prev_peserta - $data->jumlah;
}
function getSettingLike($nilai)
{
    $hasil = 0;
    $klk = Setting::where('id', 'like', '%' . $nilai . '%')->get()->first();
    if ($klk) {
        $hasil = $klk->nilai;
    }
    return $hasil;
}

function getClassKerusakan($nilai)
{
    $hasil = "";
    switch (strtolower($nilai)) {
        case 'ringan':
            $hasil = "label-info";
            break;
        case 'sedang':
            $hasil = "label-alert";
            break;
        case 'berat':
            $hasil = "label-warning";
            break;
        case 'total':
            $hasil = "label-danger";
            break;

        default:
            $hasil = "label-info";
            break;
    }
    return $hasil;
}
function getLayout()
{
    $layout = "layouts.myapp";
    switch (Auth::user()->roles->first()->role_name) {
        case 'verifikasi':
            $layout = 'layouts.myapp-verifikasi';
            break;
        case 'sekolah':
            $layout = 'layouts.myapp-sekolah';
            break;
        default:
            $layout = 'layouts.myapp';
            break;
    }
    return $layout;
}
function weekOfMonth($date)
{
    //Get the first day of the month.
    $firstOfMonth = strtotime(date("Y-m-01", $date));
    //Apply above formula.
    return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
}
function weekOfYear($date)
{
    $weekOfYear = intval(date("W", $date));
    if (date('n', $date) == "1" && $weekOfYear > 51) {
        // It's the last week of the previos year.
        return 0;
    } else if (date('n', $date) == "12" && $weekOfYear == 1) {
        // It's the first week of the next year.
        return 53;
    } else {
        // It's a "normal" week.
        return $weekOfYear;
    }
}
