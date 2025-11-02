<?php

namespace App\Http\Controllers;

use App\Akun;
use App\JenisTransaksi;
use App\Jimpitan;
use App\Jurnal;
use App\Setting;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class JimpitanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['judul'] = 'Daftar Jimpitan';
        return view('admin.jimpitan.list_jimpitan', $data);
    }
    public function list(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'no_ref',
            2 => 'ket_jimpitan',
            3 => 'tahun',
            4 => 'bulan',
            5 => 'jumlah_terima',
            6 => 'user_id',
            7 => 'id',
        );

        $totalData = Jimpitan::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts =  Jimpitan::selectRaw('jimpitans.*')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  Jimpitan::selectRaw('jimpitans.*')
                ->where('jimpitans.id', 'LIKE', "%{$search}%")
                ->orWhere('nmsubakun', 'LIKE', "%{$search}%")
                ->orWhere('ket_jimpitan', 'LIKE', "%{$search}%")
                ->orWhere('no_ref', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Jimpitan::where('jimpitans.id', 'LIKE', "%{$search}%")
                ->orWhere('nmsubakun', 'LIKE', "%{$search}%")
                ->orWhere('ket_jimpitan', 'LIKE', "%{$search}%")
                ->orWhere('no_ref', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show =  url('/') . '/storage/jimpitan/' . $post->image_url;
                $remove =  route('admin.jimpitan.remove', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['no_ref'] = $post->no_ref;
                $nestedData['tanggal_jimpitan'] = $post->tanggal_jimpitan;
                $nestedData['ket_jimpitan'] = $post->ket_jimpitan;
                $nestedData['tahun'] = $post->tahun;
                $nestedData['bulan'] = $post->bulan;
                $nestedData['periode'] = $post->periode;
                $nestedData['nominal'] = $post->nominal;
                $nestedData['jumlah_peserta'] = $post->jumlah_peserta;
                $nestedData['sub_total'] = $post->sub_total;
                $nestedData['upah'] = $post->upah;
                $nestedData['jumlah_terima'] = $post->jumlah_terima;
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
        $tahun_arr = array();
        for ($i = date('Y') - 3; $i < date('Y') + 1; $i++) {
            array_push($tahun_arr, $i);
        }
        $bulan_arr = [
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
        $data['judul'] = 'Transaksi Jimpitan Baru';
        $nominal = getSetting('nominal_jimpitan');
        $upah_opt = array(0, getSetting('upah_jimpitan'));
        $data['tahun_opt'] = $tahun_arr;
        $data['bulan_opt'] = $bulan_arr;
        $data['periode'] = weekOfMonth(strtotime(date('Y-m-d')));
        $jumlah_peserta = getSisaPeserta(date('Y'), date('m'),  $data['periode']);
        $sub_total = $jumlah_peserta * $nominal;
        $jumlah_terima = $sub_total - $upah_opt[0];

        $data['nominal'] = $nominal;
        $data['jumlah_peserta'] = $jumlah_peserta;
        $data['upah_opt'] = $upah_opt;
        $data['sub_total'] = $sub_total;
        $data['jumlah_terima'] = $jumlah_terima;

        return view('admin.jimpitan.create_jimpitan', $data);
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
                'tanggal_jimpitan' => 'required|string|max:100',
                'ket_jimpitan' => 'required|string|max:100',
                'user_id' => 'required|string|max:100',
                'jumlah_peserta' => 'required|integer',
                'jumlah_terima' => 'required|integer',
            ],
            [
                'tanggal_jimpitan.required' => 'Tanggal Jimpitan harus diisi',
                'ket_jimpitan.required' => 'Keterangan harus diisi',
                'user_id.required' => 'user id harus diisi',
                'jumlah_peserta.required' => 'Jumlah Peserta harus diisi',
                'jumlah_terima.required' => 'Jumlah Terima harus diisi',
            ]
        );
        $maxsize    = 1048576;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        // Output: 54esmdr0qf
        $acak = time() . '_';
        $jt = JenisTransaksi::where('nama_alias', 'jimpitan')->first();
        $grup = Str::random();
        $date = Carbon::createFromFormat('d/m/Y H:i:s', $request['tanggal_jimpitan']);
        $xdata = [
            'no_ref' => $request['no_ref'],
            'tanggal_jimpitan' =>  $date,
            'ket_jimpitan' => $request['ket_jimpitan'],
            'tahun' => $request['tahun'],
            'bulan' =>  $request['bulan'],
            'periode' =>  $request['periode'],
            'nominal' =>  $request['nominal'],
            'jumlah_peserta' =>  $request['jumlah_peserta'],
            'sub_total' =>  $request['sub_total'],
            'upah' =>  $request['upah'],
            'jumlah_terima' => $request['jumlah_terima'],
            'user_id' => $request['user_id'],
            'grup'  => $grup,
        ];
        $xdata1 = [
            'no_ref' => $request['no_ref'],
            'tanggal_jurnal' =>  $date,
            'ket_jurnal' => $jt->nama_jtransaksi . ', Bulan ' . $request['bulan'] . ', Tahun ' . $request['tahun'] . ', Minggu ke-' . $request['periode'],
            'jenis_transaksi_id' => $jt->id,
            'debet' =>  $request['jumlah_terima'],
            'kredit' => 0,
            'user_id' => $request['user_id'],
            'kdsubakun' => $jt->akun_debet,
            'grup'  => $grup,
        ];
        $xdata2 = [
            'no_ref' => $request['no_ref'],
            'tanggal_jurnal' =>  $date,
            'ket_jurnal' => $jt->nama_jtransaksi . ', Bulan ' . $request['bulan'] . ', Tahun ' . $request['tahun'] . ', Minggu ke-' . $request['periode'],
            'jenis_transaksi_id' => $jt->id,
            'debet' =>  0,
            'kredit' =>  $request['jumlah_terima'],
            'user_id' => $request['user_id'],
            'kdsubakun' => $jt->akun_kredit,
            'grup'  => $grup,
        ];

        if ($request->hasfile('image_url')) {
            $dest = storage_path()  . '/app/public/jimpitan/';
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
                $xdata['image_url'] = $name;
                $xdata1['image_url'] = $name;
                $xdata2['image_url'] = $name;
                // $xdata['thumb_image_url'] = $thumbname;

                // $img->save($dest . '/' . $thumbname);
                $file->move($dest, $name);
            }
        }
        Jimpitan::create($xdata);
        Jurnal::create($xdata1);
        Jurnal::create($xdata2);
        return redirect()->action('JimpitanController@index')->with('status', 'Tambah Data Sukses!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jimpitan  $Jimpitan
     * @return \Illuminate\Http\Response
     */
    public function show(Jimpitan $Jimpitan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Jimpitan  $Jimpitan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['judul'] = 'Update Jimpitan';
        $data['Jimpitan'] = Jimpitan::findOrFail($id);
        return view('admin.jimpitan.update_jimpitan', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jimpitan  $Jimpitan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'no_ref' => 'required|string|max:100',
                'ket_jimpitan' => 'required|string|max:100',
                'user_id' => 'required|string|max:100',
                'bulan' => 'required|integer',
                'jumlah' => 'required|integer',
            ],
            [
                'no_ref.required' => 'Kode Jenis harus diisi',
                'ket_jimpitan.required' => 'Keterangan harus diisi',
                'user_id.required' => 'Nama user id harus diisi',
                'bulan.required' => 'Debet harus diisi',
                'jumlah.required' => 'Debet harus diisi',
            ]
        );
        $user = Jimpitan::find($request['id']);
        if ($user) {
            $user->no_ref = $request['no_ref'];
            $user->jenis = $request['jenis'];
            $user->tahun = $request['tahun'];
            $user->bulan = $request['bulan'];
            $user->jumlah = $request['jumlah'];
            $user->user_id = $request['user_id'];
            $user->save();
        }
        return redirect()->action('JimpitanController@index')->with('status', 'Update Data Sukses!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jimpitan  $Jimpitan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rec = Jimpitan::find($id);
        if ($rec) {
            Jurnal::where('grup', $rec->grup)->delete();
            $rec->delete();
        }
        $data['message'] = "hapus data sukses";
        return response()->json($data);
    }
    public function getNamaJimpitan($tahun)
    {
        $bulans = Jimpitan::getNamaJimpitan($tahun);
        return response()->json(array('data' => $bulans));
    }
    public function getKodeJimpitan($tahun)
    {
        $kode = Jimpitan::gettahun($tahun);
        return response()->json(array('data' => $kode));
    }

    public function getJumlahPeserta($tahun, $bulan, $periode)
    {
        $count = getSisaPeserta($tahun, $bulan, $periode);
        return response()->json(array('data' => $count));
    }
}
