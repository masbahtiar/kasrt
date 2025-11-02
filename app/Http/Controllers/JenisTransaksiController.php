<?php

namespace App\Http\Controllers;

use App\Akun;
use App\JenisTransaksi;
use Illuminate\Http\Request;

class JenisTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['judul'] = 'Daftar Jenis Transaksi';
        return view('admin.jenis_transaksi.list_jenis_transaksi', $data);
    }
    public function list(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'nama_jtransaksi',
            2 => 'akun_debet',
            3 => 'akun_kredit',
            4 => 'keterangan',
            7 => 'id',
        );

        $totalData = JenisTransaksi::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts =  JenisTransaksi::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  JenisTransaksi::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama_jtransaksi', 'LIKE', "%{$search}%")
                ->orWhere('akun_debet', 'LIKE', "%{$search}%")
                ->orWhere('akun_kredit', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = JenisTransaksi::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama_jtransaksi', 'LIKE', "%{$search}%")
                ->orWhere('akun_debet', 'LIKE', "%{$search}%")
                ->orWhere('akun_kredit', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $edit =  route('admin.jenistransaksi.edit', $post->id);; //route('posts.show',$post->id);
                $remove =  route('admin.jenistransaksi.remove', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['nama_jtransaksi'] = $post->nama_jtransaksi;
                $nestedData['akun_debet'] = $post->akun_debet;
                $nestedData['akun_kredit'] = $post->akun_kredit;
                $nestedData['keterangan'] = $post->keterangan;
                $nestedData['options'] = "&emsp;<a href='{$edit}' title='UPDATE' ><span class='fas fa-edit'></span></a>
                                          &emsp;<a href='{$remove}' title='HAPUS' ><span class='fas fa-trash'></span></a>";
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
        $data['judul'] = 'Buat Jenis Transaksi Baru';
        $akun = Akun::get();
        $data['akun_debets'] = $akun;
        $data['akun_kredits'] = $akun;
        return view('admin.jenis_transaksi.create_jenis_transaksi', $data);
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
                'nama_jtransaksi' => 'required|string|max:100',
                'akun_debet' => 'required|string|max:100',
                'akun_kredit' => 'required|string|max:100',
                'keterangan' => 'required|string',
                'nama_alias' => 'required|string|unique:jenis_transaksis',
            ],
            [
                'nama_jtransaksi.required' => 'Nama Jenis Transaksi harus diisi',
                'akun_debet.required' => 'Akun Debet harus diisi',
                'akun_kredit.required' => 'Akun Kredit harus diisi',
                'keterangan.required' => 'Keterangan harus diisi',
                'nama_alias.unique' => 'Nama Alias sudah digunakan',
                'nama_alias.required' => 'Nama Alias harus diisi',
            ]
        );
        JenisTransaksi::create([
            'nama_jtransaksi' => $request['nama_jtransaksi'],
            'akun_debet' => $request['akun_debet'],
            'akun_kredit' => $request['akun_kredit'],
            'keterangan' => $request['keterangan'],
            'nama_alias' => $request['nama_alias'],
        ]);
        return redirect()->action('JenisTransaksiController@index')->with('status', 'Tambah Data Sukses!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JenisTransaksi  $jenisTransaksi
     * @return \Illuminate\Http\Response
     */
    public function show(JenisTransaksi $jenisTransaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JenisTransaksi  $jenisTransaksi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['judul'] = 'Update Jenis Transaksi';
        $akun = Akun::get();
        $data['akun_debets'] = $akun;
        $data['akun_kredits'] = $akun;
        $data['jenistransaksi'] = JenisTransaksi::findOrFail($id);
        return view('admin.jenis_transaksi.update_jenis_transaksi', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisTransaksi  $jenisTransaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'nama_jtransaksi' => 'required|string|max:100',
                'akun_debet' => 'required|string|max:100',
                'akun_kredit' => 'required|string|max:100',
                'keterangan' => 'required|string',
                'nama_alias' => 'required|string',
            ],
            [
                'nama_jtransaksi.required' => 'Nama Jenis Transaksi harus diisi',
                'akun_debet.required' => 'Akun Debet harus diisi',
                'akun_kredit.required' => 'Akun Kredit harus diisi',
                'keterangan.required' => 'Keterangan harus diisi',
                'nama_alias.required' => 'Nama Alias harus diisi',
            ]
        );
        $user = JenisTransaksi::find($request['id']);
        if ($user) {
            $user->nama_jtransaksi = $request['nama_jtransaksi'];
            $user->akun_debet = $request['akun_debet'];
            $user->akun_kredit = $request['akun_kredit'];
            $user->keterangan = $request['keterangan'];
            $user->nama_alias = $request['nama_alias'];
            $user->save();
        }
        return redirect()->action('JenisTransaksiController@index')->with('status', 'Update Data Sukses! ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisTransaksi  $jenisTransaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        JenisTransaksi::find($id)->delete();
        $data['message'] = "hapus data sukses";
        return response()->json($data);
    }
}
