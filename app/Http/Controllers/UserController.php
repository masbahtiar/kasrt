<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function index()
    {
        return view('admin/lsuser');
    }
    public function addUser()
    {
        $data['roles'] = \App\Role::get();
        return view('admin/adduser', $data);
    }
    public function updUser($id)
    {
        $data['roles'] = \App\Role::get();
        $data['user'] = User::findOrFail($id);
        return view('admin/upduser', $data);
    }
    public function lsuser(Request $request)
    {
        //$data['roles'] = \App\Role::get();
        //$data['users'] = \App\User::has('roles')->get();
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'username',
            3 => 'email',
            4 => 'id',
        );

        $totalData = User::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = \App\User::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  User::where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = \App\User::where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show =  '#'; //route('posts.show',$post->id);
                $edit =  route('admin.getupduser', $post->id);
                $remove =  route('admin.remuser', $post->id);

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['username'] = $post->username;
                $nestedData['email'] = $post->email;
                $nestedData['role'] = $post->roles->first()->role_name;
                $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='fas fa-list'></span></a>
                                          &emsp;<a href='{$edit}' title='EDIT' ><span class='fas fa-edit'></span></a>
                                          &emsp;<a href='{$remove}' title='HAPUS' ><span class='fas fa-trash-alt'></span></a>

                                          ";
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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $data)
    {
        $this->validate($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'sekolah_id' => 'required|numeric',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user = User::find($user->id);
        if ($user) $user->putRole($data['level']);
        if ($user) $user->putSekolah($data['sekolah_id']);
        return redirect()->action('UserController@index');
    }
    public function update(Request $data)
    {
        $this->validate($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'sekolah_id' => 'required|numeric',
        ]);

        $user = User::find($data['id']);
        if ($user) {
            $user->forgetRole($user->level);
            $user->forgetSekolah($user->sekolah_id);
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->save();
            $user->putRole($data['level']);
            $user->putSekolah($data['sekolah_id']);
            return redirect()->action('UserController@index');
        }
    }
    public function updPassword(Request $data)
    {
        $this->validate($data, [
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::find($data['id']);
        if ($user) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }
        return redirect()->route('admin.getupduser', $data['id']);
    }
    public function remove($id)
    {
        $user = User::findOrFail($id);
        $user->forgetRole($user->roles()->first()->role_name);
        $user->forgetSekolah($id);
        $user->delete();
        $data['message'] = "sukses";
        return response()->json($data);
    }
}
