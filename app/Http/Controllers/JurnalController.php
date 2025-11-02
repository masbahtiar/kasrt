<?php

namespace App\Http\Controllers;

use App\Akun;
use App\JenisTransaksi;
use App\Jurnal;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['judul'] = 'Daftar Jurnal';
        return view('admin.jurnal.list_jurnal', $data);
    }
    public function list(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'no_ref',
            2 => 'ket_jurnal',
            3 => 'jenis_transaksi_id',
            4 => 'debet',
            5 => 'kredit',
            6 => 'user_id',
            7 => 'id',
        );

        $totalData = Jurnal::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts =  Jurnal::selectRaw('jurnals.*,akuns.nmsubakun,nama_jtransaksi')
                ->join('akuns', 'akuns.kdsubakun', '=', 'jurnals.kdsubakun')
                ->join('jenis_transaksis', 'jenis_transaksis.id', '=', 'jurnals.jenis_transaksi_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  Jurnal::selectRaw('jurnals.*,akuns.nmsubakun,nama_jtransaksi')
                ->join('akuns', 'akuns.kdsubakun', '=', 'jurnals.kdsubakun')
                ->join('jenis_transaksis', 'jenis_transaksis.id', '=', 'jurnals.jenis_transaksi_id')
                ->where('jurnals.id', 'LIKE', "%{$search}%")
                ->orWhere('nmsubakun', 'LIKE', "%{$search}%")
                ->orWhere('ket_jurnal', 'LIKE', "%{$search}%")
                ->orWhere('no_ref', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Jurnal::selectRaw('jurnals.*,akuns.nmsubakun,nama_jtransaksi')
                ->join('akuns', 'akuns.kdsubakun', '=', 'jurnals.kdsubakun')
                ->join('jenis_transaksis', 'jenis_transaksis.id', '=', 'jurnals.jenis_transaksi_id')
                ->where('jurnals.id', 'LIKE', "%{$search}%")
                ->orWhere('nmsubakun', 'LIKE', "%{$search}%")
                ->orWhere('ket_jurnal', 'LIKE', "%{$search}%")
                ->orWhere('no_ref', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show =  url('/') . '/storage/jurnal/' . $post->image_url;
                $remove =  route('admin.jurnal.remove', $post->grup);

                $nestedData['id'] = $post->id;
                $nestedData['no_ref'] = $post->no_ref;
                $nestedData['kdsubakun'] = $post->kdsubakun;
                $nestedData['nmsubakun'] = $post->nmsubakun;
                $nestedData['jenis_transaksi'] = $post->nama_jtransaksi;
                $nestedData['ket_jurnal'] = $post->ket_jurnal;
                $nestedData['debet'] = $post->debet;
                $nestedData['kredit'] = $post->kredit;
                $nestedData['user_id'] = $post->user_id;
                $nestedData['options'] = "<div class='d-flex justify-content-around'>";
                if (!empty($post->image_url)) {
                    $nestedData['options'] .= "<a href='{$show}' title='UPDATE' data-toggle='lightbox' data-title='" . $post->nama_jtransaksi . "'><span class='fas fa-image'></span></a>";
                }
                $nestedData['options'] .= "<a class='del-btn' href='{$remove}' title='HAPUS' ><span class='fas fa-trash'></span></a></div>";
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        //  return response()
        //->json($json_data);


        echo json_encode($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['judul'] = 'Transaksi Jurnal Baru';
        $data['jenis_transaksis'] = JenisTransaksi::get();
        return view('admin.jurnal.create_jurnal', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'tanggal_jurnal' => 'required|string|max:100',
                'ket_jurnal' => 'required|string|max:100',
                'user_id' => 'required|string|max:100',
                'jumlah' => 'required|integer',
            ],
            [
                'tanggal_jurnal.required' => 'Tanggal Jurnal harus diisi',
                'ket_jurnal.required' => 'Keterangan harus diisi',
                'user_id.required' => 'user id harus diisi',
                'jumlah.required' => 'Jumlah harus diisi',
            ]
        );
        $maxsize    = 1048576;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        // Output: 54esmdr0qf
        $acak = time() . '_';
        $jt = JenisTransaksi::find($request['jenis_transaksi_id']);
        $grup = Str::random();
        $date = Carbon::createFromFormat('d/m/Y H:i:s', $request['tanggal_jurnal']);
        $xdata1 = [
            'no_ref' => $request['no_ref'],
            'tanggal_jurnal' =>  $date,
            'ket_jurnal' => $request['ket_jurnal'],
            'jenis_transaksi_id' => $request['jenis_transaksi_id'],
            'debet' =>  $request['jumlah'],
            'kredit' => 0,
            'user_id' => $user_id,
            'kdsubakun' => $jt->akun_debet,
            'grup'  => $grup,
        ];
        $xdata2 = [
            'no_ref' => $request['no_ref'],
            'tanggal_jurnal' =>  $date,
            'ket_jurnal' => $request['ket_jurnal'],
            'jenis_transaksi_id' => $request['jenis_transaksi_id'],
            'debet' =>  0,
            'kredit' =>  $request['jumlah'],
            'user_id' => $user_id,
            'kdsubakun' => $jt->akun_kredit,
            'grup'  => $grup,
        ];
        if ($request->hasfile('image_url')) {
            $dest = storage_path()  . '/app/public/jurnal/';
            if (!is_dir($dest)) {
                mkdir($dest);
            }
            $file = $request->file('image_url');
            if (($file->getSize() < $maxsize)) {
                $name = $acak . "_" . $file->getClientOriginalName();
                // $thumbname = "thumb_" . $name;
                // $img = Image::make($file->getRealPath(), array(
                //     'width' => 100,
                //     'height' => 100,
                //     'grayscale' => false
                // ));
                $xdata1['image_url'] = $name;
                $xdata2['image_url'] = $name;
                // $xdata['thumb_image_url'] = $thumbname;

                // $img->save($dest . '/' . $thumbname);
                $file->move($dest, $name);
            }
        }
        Jurnal::create($xdata1);
        Jurnal::create($xdata2);
        return redirect()->action('JurnalController@index')->with('status', 'Tambah Data Sukses!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jurnal  $Jurnal
     * @return \Illuminate\Http\Response
     */
    public function show(Jurnal $Jurnal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Jurnal  $Jurnal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['judul'] = 'Update Jurnal';
        $data['Jurnal'] = Jurnal::findOrFail($id);
        return view('admin.jurnal.update_jurnal', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jurnal  $Jurnal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'no_ref' => 'required|string|max:100',
                'ket_jurnal' => 'required|string|max:100',
                'user_id' => 'required|string|max:100',
                'debet' => 'required|integer',
                'kredit' => 'required|integer',
            ],
            [
                'no_ref.required' => 'Kode Jenis harus diisi',
                'ket_jurnal.required' => 'Keterangan harus diisi',
                'user_id.required' => 'Nama user id harus diisi',
                'debet.required' => 'Debet harus diisi',
                'kredit.required' => 'Debet harus diisi',
            ]
        );
        $user = Jurnal::find($request['id']);
        if ($user) {
            $user->no_ref = $request['no_ref'];
            $user->jenis = $request['jenis'];
            $user->jenis_transaksi_id = $request['jenis_transaksi_id'];
            $user->debet = $request['debet'];
            $user->kredit = $request['kredit'];
            $user->user_id = $user_id;
            $user->save();
        }
        return redirect()->action('JurnalController@index')->with('status', 'Update Data Sukses!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jurnal  $Jurnal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Jurnal::where('grup', $id)->delete();
        $data['message'] = "hapus data sukses";
        return response()->json($data);
    }
    public function getNamaJurnal($jenis_transaksi_id)
    {
        $debets = Jurnal::getNamaJurnal($jenis_transaksi_id);
        return response()->json(array('data' => $debets));
    }
    public function getKodeJurnal($jenis_transaksi_id)
    {
        $kode = Jurnal::getjenis_transaksi_id($jenis_transaksi_id);
        return response()->json(array('data' => $kode));
    }

    public function saveNeraca(Request $request)
    {
        $grup = Str::random();
        $results = array('success' => false, 'message' => 'Penyimpanan Gagal', 'data' => []);
        if ($request->isMethod('post')) {
            // $post = $request->getParsedBody();
            $user_id = auth()->user()->id;
            $year = $request['year'];
            $cyear = Carbon::now()->year;
            $date = Carbon::create($year + 1, 1, 1, 1, 1, 0, 'Asia/Jakarta');
            if ($year < $cyear) {
                if ($data = Jurnal::where('ket_jurnal', 'Neraca Saldo ' . $year)) {
                    $data->delete();
                }
                $pre = Jurnal::selectRaw('kdjenis,akuns.kdsubakun,nmsubakun,CASE WHEN kdjenis >1 AND kdjenis<5  THEN  if(sum(debet)>sum(kredit),sum(debet)-sum(kredit),sum(kredit)-sum(debet)) ELSE
            sum(debet)-sum(kredit) END AS saldo')
                    ->join('akuns', 'jurnals.kdsubakun', 'akuns.kdsubakun')
                    ->orderBy('kdjenis', 'asc')
                    ->whereRaw('YEAR(tanggal_jurnal)=?', [$year])->groupBy('nmsubakun', 'kdjenis', 'kdsubakun');
                $result = $pre->limit(100)->offset(0)->get();
                $data = [];
                $j_pendapatan = 0;
                $j_beban = 0;
                $jt = JenisTransaksi::where('nama_alias', 'pendapatan-rt')->first();
                foreach ($result as $key => $value) {
                    $debit = $value->saldo;
                    $kredit = 0;
                    if ($value->kdjenis > 1 && $value->kdjenis < 5) {
                        $debit = 0;
                        $kredit = $value->saldo;
                    }
                    if ($value->kdjenis != 4 && $value->kdjenis != 5) {
                        $xdata1 = [
                            'no_ref' => 'Neraca ' . $year,
                            'tanggal_jurnal' =>  $date,
                            'ket_jurnal' => 'Neraca Saldo ' . $year,
                            'jenis_transaksi_id' => $jt->id,
                            'debet' =>  $debit,
                            'kredit' => $kredit,
                            'user_id' => $user_id,
                            'kdsubakun' => $value->kdsubakun,
                            'grup'  => $grup,
                        ];

                        //array_push($data,$ju1);
                        Jurnal::create($xdata1);
                    }
                    if ($value->kdjenis == 4) {
                        $j_pendapatan += $debit > $kredit ? $debit - $kredit : $kredit - $debit;
                    }
                    if ($value->kdjenis == 5) {
                        $j_beban += $debit > $kredit ? $debit - $kredit : $kredit - $debit;
                    }
                }

                $debet = 0;
                $kredit = $j_pendapatan - $j_beban;
                $xdata1 = [
                    'no_ref' => 'Neraca ' . date('Y'),
                    'tanggal_jurnal' =>  $date,
                    'ket_jurnal' => 'Neraca Saldo ' . $year,
                    'jenis_transaksi_id' => $jt->id,
                    'debet' =>  $debet,
                    'kredit' => $kredit,
                    'user_id' => $user_id,
                    'kdsubakun' => $jt->akun_kredit,
                    'grup'  => $grup,
                ];

                //array_push($data,$ju1);
                Jurnal::create($xdata1);
                $results = array('success' => true, 'message' => 'Penyimpanan Sukses', 'data' => $data);
            } else {
                $results = array('success' => false, 'message' => 'tahun harus sebelum tahun sekarang', 'data' => []);
            }
            return redirect()->action('JurnalController@saveNeraca')->with('status', 'Update Neraca Sukses!');
        } else {
            $year = Carbon::now()->year;
            $years = [];
            for ($i = $year - 1; $i > $year - 6; $i--) {
                array_push($years, $i);
            }
            $data['judul'] = 'Update Neraca';
            $data['years'] = $years;
            return view('admin.neraca.update-neraca', $data);
        }
    }
}
