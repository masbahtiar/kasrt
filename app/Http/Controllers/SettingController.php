<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{
    public function getAdd()
    {
        return view('admin/addsetting');
    }
    public function getUpdate($id)
    {
        $data['setting'] = Setting::findOrFail($id);
        return view('admin/updsetting', $data);
    }
    public function list()
    {
        return view('admin/lssetting');
    }
    protected function add(Request $data)
    {
        $this->validate($data, [
            'id'     => 'required|string|max:100|unique:settings',
            'nilai' => 'required|string|max:100',
            'keterangan' => 'required|string|max:100'
        ]);

        $user = Setting::create(
            [
                'id'    =>  $data['id'],
                'nilai' => $data['nilai'],
                'keterangan' => $data['keterangan'],
            ],
            [
                'nmdesa.unique' => 'ID sudah digunakan'
            ]
        );
        return redirect()->action('SettingController@list');
    }
    public function update(Request $data)
    {
        $this->validate($data, [
            'id'     => 'required|string|max:100',
            'nilai' => 'required|string|max:100',
            'keterangan' => 'required|string|max:100'
        ]);

        $user = Setting::find($data['id']);
        if ($user) {
            $user->nilai = $data['nilai'];
            $user->keterangan = $data['keterangan'];
            $user->save();
        }
        return redirect()->action('SettingController@list');
    }
    public function getLsSetting(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'nilai',
            2 => 'keterangan',
        );

        $totalData = Setting::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts =  Setting::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  Setting::where('id', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Setting::where('id', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $edit =  route('admin.getupdsetting', $post->id);; //route('posts.show',$post->id);

                $nestedData['id'] = $post->id;
                $nestedData['nilai'] = $post->nilai;
                $nestedData['keterangan'] = $post->keterangan;
                $nestedData['options'] = "&emsp;<a href='{$edit}' title='UPDATE' ><span class='fas fa-edit'></span></a>";
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
}
