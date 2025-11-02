<?php

namespace App\Http\Controllers;

use App\FlashMessage;
use App\Role;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class FlashMessageController extends Controller
{
    public function getAdd()
    {
        $data['roles'] = \App\Role::get();
        $data['judul'] = 'Tambah Flash Message';
        return view('admin/flash-message/add-flash-message', $data);
    }
    public function getUpdate($id)
    {
        $data['roles'] = \App\Role::get();
        $data['judul'] = 'Update Flash Message';
        $data['artikel'] = FlashMessage::findOrFail($id);
        return view('admin/flash-message/upd-flash-message', $data);
    }
    public function index()
    {
        $data['judul'] = 'Daftar Flash Message';
        return view('admin.flash-message.ls-flash-message', $data);
    }
    public function showFlashMessage(Request $request)
    {
        $date = Carbon::parse(date('Y-m-d' . ' 24:00:00'));
        $posts = FlashMessage::join('users', 'users.id', '=', 'flash_messages.user_id')
            ->select('flash_messages.*', 'users.name')
            // ->join('role_flash_messages', 'role_flash_messages.flash_message_id', '=', 'flash_messages.id')
            // ->where('role_flash_messages.role_id', $request->user()->roles()->first()->role_id)
            ->where('aktif', 'yes')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->offset(0)
            ->limit(5)
            ->orderBy('start_date', 'desc')
            ->get();
        $data = [];
        $r = $request->user()->roles()->first()->role_name;
        foreach ($posts as $k => $v) {
            if ($v->hasRole($r)) {
                $data[$k] = $v;
            }
        }
        return response()->json($data);
    }
    public function list(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'judul',
            3 => 'start_date',
            4 => 'end_date',
            5 => 'aktif',
            6 => 'id',
        );
        $start_date = $request->input('start_date');

        $totalData = FlashMessage::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = FlashMessage::join('users', 'users.id', '=', 'flash_messages.user_id')
                ->select('flash_messages.*', 'users.name')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $posts = FlashMessage::where(function ($q) use ($search) {
                $q->where('flash_messages.id', 'LIKE', "%{$search}%")
                    ->orWhere('judul', 'LIKE', "%{$search}%")
                    ->orWhere('isi_pesan', 'LIKE', "%{$search}%");
            })
                ->join('users', 'users.id', '=', 'flash_messages.user_id')
                ->select('flash_messages.*', 'users.name')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = FlashMessage::where('start_date', $start_date)
                ->where(function ($q) use ($search) {
                    $q->where('flash_messages.id', 'LIKE', "%{$search}%")
                        ->orWhere('judul', 'LIKE', "%{$search}%")
                        ->orWhere('isi_pesan', 'LIKE', "%{$search}%");
                })
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $edit = route('admin.getupdflashmessage', $post->id); //route('posts.show',$post->id);
                $remove = route('admin.remflashmessage', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['judul'] = $post->judul;
                $nestedData['start_date'] = $post->start_date;
                $nestedData['end_date'] = $post->end_date;
                $nestedData['name'] = $post->name;
                $nestedData['aktif'] = $post->aktif;
                $nestedData['options'] = '<div class="btn-group dropleft">
    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Menu
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="' . $edit . '" title="UPDATE"><i class="fas fa-edit"></i> UPDATE DATA</a>
        <a class="dropdown-item" href="' . $remove . '" title="HAPUS"><i class="fas fa-trash-alt"></i> HAPUS</a>
    </div>
</div>';

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        // return response()
        //->json($json_data);


        echo json_encode($json_data);
    }
    protected function add(Request $data)
    {
        $this->validate(
            $data,
            [
                'judul' => 'required|string|max:250',
                'isi_pesan' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'user_id' => 'required',
                'level' => 'required'
            ],
            [
                'judul.required' => 'Judul harus diisi',
                'isi_pesan.required' => 'Isi pesan Harus Diisi',
                'user_id.required' => 'User Id harus diisi',
            ]
        );
        //07/04/2021 12:00 AM - 07/04/2021 11:59 PM
        $start_end_date = explode(' - ', $data['start_end_date']);
        foreach ($start_end_date as $k => $v) {
            $start_end_date[$k] = Carbon::parse($v);
        }

        $xdata = [
            'judul' => $data['judul'],
            'isi_pesan' => $data['isi_pesan'],
            'start_date' => $start_end_date[0],
            'end_date' => $start_end_date[1],
            'user_id' => $data['user_id'],
            'aktif' => $data['aktif'],
        ];

        $user = FlashMessage::create($xdata);
        $user = FlashMessage::find($user->id);
        if ($user) {
            foreach ($data['level'] as $k => $v) {
                $user->putRole($v);
            }
        }
        return redirect()->route('admin.flashmessage.list', ['id' => $data['start_date']]);
    }
    public function update(Request $data)
    {
        $this->validate(
            $data,
            [
                'judul' => 'required|string|max:200',
                'isi_pesan' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'level' => 'required'
            ],
            [
                'judul.required' => 'Judul harus diisi',
                'isi_pesan.required' => 'Isi Pesan Harus Diisi',
            ]
        );

        //07/04/2021 12:00 AM - 07/04/2021 11:59 PM
        $start_end_date = explode(' - ', $data['start_end_date']);
        foreach ($start_end_date as $k => $v) {
            $start_end_date[$k] = Carbon::parse($v);
        }

        $user = FlashMessage::find($data['id']);
        $roles = Role::get();
        if ($user) {
            foreach ($roles as $k => $v) {
                $user->forgetRole($v->role_name);
            }
            $user->judul = $data['judul'];
            $user->isi_pesan = $data['isi_pesan'];
            $user->start_date =  $start_end_date[0];
            $user->end_date =  $start_end_date[1];
            $user->user_id = $data['user_id'];
            $user->aktif = $data['aktif'];
            $user->save();
            foreach ($data['level'] as $k => $v) {
                $user->putRole($v);
            }
        }
        return redirect()->route('admin.flashmessage.list');
    }
    public function remove($id)
    {
        FlashMessage::find($id)->delete();
        $data['message'] = "sukses";
        return response()->json($data);
    }
}
