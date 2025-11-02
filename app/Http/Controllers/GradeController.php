<?php

namespace App\Http\Controllers;

use App\Grade;

use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function getAdd()
    {
        return view('admin/addgrade');
    }
    public function getUpdate($id)
    {
        $data['grade'] = Grade::findOrFail($id);
        return view('admin/updgrade', $data);
    }
    public function list()
    {
        return view('admin/lsgrade');
    }
    public function getList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'nm_grade',
            2 => 'id',
        );

        $totalData = Grade::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts =  Grade::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  Grade::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nm_grade', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Grade::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nm_grade', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $edit =  route('admin.getupdgrade', $post->id);; //route('posts.show',$post->id);
                $remove =  route('admin.remgrade', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['nm_grade'] = $post->nm_grade;
                $nestedData['options'] = "&emsp;<a href='{$edit}' title='UPDATE' ><span class='fas fa-edit'></span></a>
                                          &emsp;<a href='{$remove}' title='HAPUS' ><span class='fas fa-trash-alt-circle'></span></a>";
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
    protected function add(Request $data)
    {
        $this->validate(
            $data,
            [
                'nm_grade' => 'required|string|max:50|unique:grades',
            ],
            [
                'nm_grade.required' => 'Nama Grade harus diisi',
                'nm_grade.unique' => 'Nama Grade sudah digunakan'
            ]
        );

        $user = Grade::create([
            'nm_grade' => $data['nm_grade'],
        ]);
        return redirect()->action('GradeController@list');
    }
    public function update(Request $data)
    {
        $this->validate($data, [
            'nm_grade' => 'required|string|max:50',
        ]);

        $user = Grade::find($data['id']);
        if ($user) {
            $user->nm_grade = $data['nm_grade'];
            $user->save();
        }
        return redirect()->action('GradeController@list');
    }

    public function remove($id)
    {
        Grade::find($id)->delete();
        $data['message'] = "sukses";
        return response()->json($data);
    }
}
