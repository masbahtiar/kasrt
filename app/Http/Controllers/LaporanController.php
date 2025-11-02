<?php

namespace App\Http\Controllers;

use App\Akun;
use App\Jimpitan;
use App\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use PDF;

class LaporanController extends Controller
{
    public function lapBukuBesar(Request $request, Response $response, $kdakun = null)
    {
        //$pdf->setPaper([0, 0, 685.98, 396.85], 'landscape');
        $data['judul'] = "BUKU BESAR";
        $data['sub_judul'] = "PERIODE";
        $from = Carbon::now()->subDays(30);
        $to = Carbon::now();
        if ($from->year < $to->year) {
            $from = Carbon::now()->setDay(1);
        }
        $akun = Akun::get();
        $data['kdsubakuns'] = $akun;
        $start = $request->has('start_date') ? Carbon::createFromFormat('d/m/Y', $request['start_date'])->setTime(1, 0, 0)  : $from->setTime(1, 0, 0);;
        $end = $request->has('end_date') ? Carbon::createFromFormat('d/m/Y', $request['end_date'])->setTime(23, 59, 59)  : $to->setTime(23, 59, 59);
        $data['start_date'] = $start;
        $data['end_date'] = $end;
        $saldo_end = $request->has('end_date') ? Carbon::createFromFormat('d/m/Y', $request['start_date'])->setTime(0, 1, 0) : $from->setTime(0, 1, 0);

        $year = $start->year;
        $saldo_start = Carbon::createFromDate($year, 1, 1)->setTime(0, 0, 0);
        $kdsubakun =  $kdakun ? $kdakun : $akun[0]->kdsubakun;
        $data['kdsubakun'] = $kdsubakun;
        $data['nmsubakun'] = Akun::getAkun($kdsubakun)->nmsubakun;

        $page = $request->has('page') ? $request['page'] : 1;
        $saldo_awal = 0;
        // $page = $request[['page'] ? $request[['page'] : 1;

        $data["page"] = $page;
        if ($start->year != $end->year) {
            $start->setDate($end->year, $end->month, 1);
            return redirect('laporan/bukubesar')->with('error', 'Tahun harus sama');
        } else {
            $pre = Jurnal::selectRaw('jurnals.*,nmsubakun')
                ->join('akuns', 'jurnals.kdsubakun', 'akuns.kdsubakun')
                ->where('jurnals.kdsubakun', $kdsubakun)
                ->whereBetween('jurnals.tanggal_jurnal', [$start, $end])
                ->orderBy('jurnals.tanggal_jurnal', 'asc');
            $result = $pre->paginate(100)->appends(request()->query());

            if ($page > 1) {
                $saldo_end = Carbon::createFromFormat('Y-m-d H:i:s', $result[0]->tanggal_jurnal)->subMinute();
            }
            $pre = Jurnal::selectRaw('nmsubakun, if(sum(debet)>sum(kredit),sum(debet)-sum(kredit),sum(kredit)-sum(debet)) AS saldo')
                ->join('akuns', 'jurnals.kdsubakun', 'akuns.kdsubakun')
                ->where('jurnals.kdsubakun', $kdsubakun)
                ->whereBetween('jurnals.tanggal_jurnal', [$saldo_start, $saldo_end])
                ->groupBy('nmsubakun', 'kdjenis');
            $result1 = $pre->get()->first();
            if ($result1) {
                $saldo_awal = $result1->saldo;
            }
            $data['saldo_awal'] = $saldo_awal;
            $kdjenis = (int)substr($kdsubakun, 0, 1);
            if ($kdjenis > 1 && $kdjenis < 5) {
                foreach ($result as $key => $value) {
                    $saldo_awal -= $value->debet;
                    $saldo_awal += $value->kredit;
                    $result[$key]['saldo'] = $saldo_awal;
                }
            } else {
                foreach ($result as $key => $value) {
                    $saldo_awal += $value->debet;
                    $saldo_awal -= $value->kredit;
                    $result[$key]['saldo'] = $saldo_awal;
                }
            }

            $data['bukubesars'] = $result;
            if ($request->has('download')) {
                $data['ketua_rt'] = getSetting('ketua-rt');
                $data['bendahara_rt'] = getSetting('bendahara-rt');
                $pdf = PDF::loadView('admin/laporan/prn_buku_besar', $data)->setPaper('f4', 'portrait')->setWarnings(false);
                return $pdf->stream('buku_besar.pdf');
            }
            return view('admin.laporan.lap_buku_besar', $data);
        }
    }

    public function lapRekapJimpitan(Request $request)
    {
        //$pdf->setPaper([0, 0, 685.98, 396.85], 'landscape');
        $data['judul'] = "REKAP JIMPITAN";
        $bulan_arr = [
            0 => '== All Bulan ==',
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $tahun_arr = array();
        for ($i = date('Y') - 3; $i < date('Y') + 1; $i++) {
            array_push($tahun_arr, $i);
        }
        $data['tahun_opt'] = $tahun_arr;
        $data['bulan_opt'] = $bulan_arr;

        $page = $request->has('page') ? $request['page'] : 1;
        $data['tahun'] = $request->has('tahun') ? $request['tahun'] : date('Y');
        $data['bulan'] = $request->has('bulan') ? $request['bulan'] : date('n');

        $data["page"] = $page;

        $sql = Jimpitan::selectRaw('sum(jumlah_terima) as total_terima')
            ->where('tahun', $data['tahun']);
        if ($data['bulan'] > 0) {
            $sql = $sql->where('bulan', $data['bulan']);
        }
        $rsql = $sql->get()->first();
        $data['total_terima'] = $rsql['total_terima'];

        $pre = Jimpitan::selectRaw('tahun,bulan,nominal, sum(jumlah_peserta) as jumlah_peserta, sum(sub_total) as sub_total,sum(upah) as upah, sum(jumlah_terima) as jumlah_terima, periode')
            ->where('tahun', $data['tahun']);
        if ($data['bulan'] > 0) {
            $pre = $pre->where('bulan', $data['bulan']);
        }

        $pre->groupBy('tahun')
            ->groupBy('bulan')
            ->groupBy('periode')
            ->groupBy('nominal')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->orderBy('periode', 'asc');
        $result = $pre->paginate(50);

        $data['jimpitans'] = $result;
        if ($request->has('download')) {
            $data['ketua_rt'] = getSetting('ketua-rt');
            $data['bendahara_rt'] = getSetting('bendahara-rt');
            $pdf = PDF::loadView('admin/laporan/prn_rekap_jimpitan', $data)->setPaper('f4', 'portrait')->setWarnings(false);
            return $pdf->stream('jimpitan.pdf');
        }
        return view('admin.laporan.lap_rekap_jimpitan', $data);
    }
    public function lapNeraca(Request $request)
    {
        //$pdf->setPaper([0, 0, 685.98, 396.85], 'landscape');
        $data['judul'] = "NERACA";
        $bulan_arr = [
            0 => '== All Bulan ==',
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $tahun_arr = array();
        for ($i = date('Y') - 3; $i < date('Y') + 1; $i++) {
            array_push($tahun_arr, $i);
        }
        $data['tahun_opt'] = $tahun_arr;
        $data['bulan_opt'] = $bulan_arr;

        $page = $request->has('page') ? $request['page'] : 1;
        $data['tahun'] = $request->has('tahun') ? $request['tahun'] : date('Y');
        $data['bulan'] = $request->has('bulan') ? $request['bulan'] : date('n');

        $data["page"] = $page;

        $pre = Jurnal::selectRaw('nmsubakun,kdjenis,if(kdjenis=1 or kdjenis=5,if(sum(debet)>sum(kredit),sum(debet)-sum(kredit),sum(kredit)-sum(debet)),0) as debet,
        if(kdjenis >1 and kdjenis<5,if(sum(debet)>sum(kredit),sum(debet)-sum(kredit),sum(kredit)-sum(debet)),0) as kredit')
            ->join('akuns', 'jurnals.kdsubakun', 'akuns.kdsubakun')
            ->orderBy('kdjenis', 'asc');
        if ($data['bulan'] > 0) {
            $pre = $pre->whereRaw('MONTH(tanggal_jurnal)<=?', $data['bulan']);
        }
        $pre->whereRaw('YEAR(tanggal_jurnal)=?', [$data['tahun']])->groupBy('nmsubakun', 'kdjenis');
        $result = $pre->paginate(100);
        $tot_debet = 0;
        $tot_kredit = 0;
        foreach ($result as $key => $value) {
            $tot_debet += $value->debet;
            $tot_kredit += $value->kredit;
        }
        $data['neracas'] = $result;
        $data['tot_debet'] = $tot_debet;
        $data['tot_kredit'] = $tot_kredit;
        if ($request->has('download')) {
            $data['ketua_rt'] = getSetting('ketua-rt');
            $data['bendahara_rt'] = getSetting('bendahara-rt');

            $pdf = PDF::loadView('admin/laporan/prn_neraca', $data)->setPaper('f4', 'portrait')->setWarnings(false);
            return $pdf->stream('neraca.pdf');
        }
        return view('admin.laporan.lap_neraca', $data);
    }
}
