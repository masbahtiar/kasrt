<?php

namespace App\Http\Controllers;

use App\Akun;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['judul'] = 'Daftar Akun';
        return view('admin.akun.list_akun', $data);
    }
    public function list(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'kdjenis',
            2 => 'jenis',
            3 => 'kdakun',
            4 => 'nmakun',
            5 => 'kdsubakun',
            6 => 'nmsubakun',
            7 => 'id',
        );

        $totalData = Akun::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts =  Akun::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  Akun::where('id', 'LIKE', "%{$search}%")
                ->orWhere('jenis', 'LIKE', "%{$search}%")
                ->orWhere('nmakun', 'LIKE', "%{$search}%")
                ->orWhere('nmsubakun', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Akun::where('id', 'LIKE', "%{$search}%")
                ->orWhere('jenis', 'LIKE', "%{$search}%")
                ->orWhere('nmakun', 'LIKE', "%{$search}%")
                ->orWhere('nmsubakun', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $edit =  route('admin.akun.edit', $post->id);; //route('posts.show',$post->id);
                $remove =  route('admin.akun.remove', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['kdjenis'] = $post->kdjenis;
                $nestedData['jenis'] = $post->jenis;
                $nestedData['kdakun'] = $post->kdakun;
                $nestedData['nmakun'] = $post->nmakun;
                $nestedData['kdsubakun'] = $post->kdsubakun;
                $nestedData['nmsubakun'] = $post->nmsubakun;
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
        $data['judul'] = 'Buat Akun Baru';
        $data['jenises'] = Akun::select('kdjenis', 'jenis')->distinct()->get();
        if (count($data['jenises']) > 0) {
            $kdjenis = $data['jenises'][0]->kdjenis;
            $data['nmakuns'] = Akun::getNamaAkun($kdjenis);
        } else {
            $data['nmakuns'] = Akun::select('kdakun', 'nmakun')->distinct()->get();
        }
        return view('admin.akun.create_akun', $data);
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
                'kdjenis' => 'required|string|max:100',
                'subakun' => 'required|string|max:100',
                'nmsubakun' => 'required|string|max:100',
                'kdsubakun' => 'required|string|unique:akuns',
            ],
            [
                'kdjenis.required' => 'Kode Jenis harus diisi',
                'subakun.required' => 'Subakun harus diisi',
                'nmsubakun.required' => 'Nama Subakun harus diisi',
                'kdsubakun.unique' => 'Kode subakun sudah digunakan',
                'kdsubakun.required' => 'Kode Subakun harus diisi',
            ]
        );
        Akun::create([
            'kdjenis' => $request['kdjenis'],
            'jenis' => $request['jenis'],
            'kdakun' => $request['kdakun'],
            'nmakun' => $request['nmakun'],
            'kdsubakun' => $request['kdsubakun'],
            'nmsubakun' => $request['nmsubakun'],
        ]);
        return redirect()->action('AkunController@index')->with('status', 'Tambah Data Sukses!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Akun  $akun
     * @return \Illuminate\Http\Response
     */
    public function show(Akun $akun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Akun  $akun
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['judul'] = 'Update Akun';
        $data['akun'] = Akun::findOrFail($id);
        return view('admin.akun.update_akun', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Akun  $akun
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'kdjenis' => 'required|string|max:100',
                'nmsubakun' => 'required|string|max:100',
                'kdsubakun' => 'required|string',
            ],
            [
                'kdjenis.required' => 'Kode Jenis harus diisi',
                'nmsubakun.required' => 'Nama Subakun harus diisi',
                'kdsubakun.required' => 'Kode Subakun harus diisi',
            ]
        );
        $user = Akun::find($request['id']);
        if ($user) {
            $user->kdjenis = $request['kdjenis'];
            $user->jenis = $request['jenis'];
            $user->kdakun = $request['kdakun'];
            $user->nmakun = $request['nmakun'];
            $user->kdsubakun = $request['kdsubakun'];
            $user->nmsubakun = $request['nmsubakun'];
            $user->save();
        }
        return redirect()->action('AkunController@index')->with('status', 'Update Data Sukses!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Akun  $akun
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Akun::find($id)->delete();
        $data['message'] = "hapus data sukses";
        return response()->json($data);
    }
    public function getNamaAkun($kdakun)
    {
        $nmakuns = Akun::getNamaAkun($kdakun);
        return response()->json(array('data' => $nmakuns));
    }
    public function getKodeAkun($kdakun)
    {
        $kode = Akun::getKdAkun($kdakun);
        return response()->json(array('data' => $kode));
    }
}
