<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyMail;
use App\User;
use App\VerifyUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:20|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|max:20confirmed',
            ],
            [
                'username.required' => 'Username harus diisi',
                'name.required' => 'Nama Sekolah harus diisi',
                'username.unique' => 'UserName Sudah Digunakan',
                'email.unique' => 'Email Sudah Digunakan',
                'email.required' => 'Email harus diisi',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password min 8 karakter',
                'password.max' => 'Password max 20 karakter',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verified' => False
        ]);
        $user = User::find($user->id);
        if ($user) $user->putRole('sekolah');
        if ($user) $user->putSekolah($data['sekolah_id']);

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => Str::random(40)
        ]);
        Mail::to($user->email)->send(new VerifyMail($user));
        return $user;
    }

    public function getResendEmail()
    {
        $data['judul'] = "Kirim Ulang Email Verifikasi";
        return view('admin/resend-email', $data);
    }

    public function resendEmail(Request $data)
    {
        $this->validate($data, [
            'email' => 'required|email',
        ]);
        $user = User::where('email', $data['email'])->get()->first();

        if ($user) {
            $verifyUser = VerifyUser::create([
                'user_id' => $user->id,
                'token' => Str::random(40)
            ]);
            Mail::to($user->email)->send(new VerifyMail($user));
        } else {
            return redirect('/login')->with('warning', "Maaf email Anda tidak terdaftar.");
        }
        return redirect('/login')->with('status', "Kami mengirimi Ulang Anda kode aktivasi. Periksa email Anda dan klik tautan untuk memverifikasi.");
    }
    public function getChangeEmail()
    {
        $data['judul'] = "Ganti Email ";
        return view('admin/change-email', $data);
    }

    public function changeEmail(Request $data)
    {
        $this->validate($data, [
            'username'  => 'required',
            'password'  => 'required',
            'email'     => 'required|email',
        ]);

        $user = User::where('username', $data['username'])->get()->first();

        if ($user) {
            $check = Hash::check($data['password'], $user->password);
            if ($check) {
                $user->email = $data['email'];
                $user->save();
                VerifyUser::create([
                    'user_id' => $user->id,
                    'token' => Str::random(40)
                ]);
                Mail::to($user->email)->send(new VerifyMail($user));
            } else {
                return redirect('/user/changeemail')->with('warning', "Maaf username atau password salah.");
            }
        } else {
            return redirect('/user/changeemail')->with('warning', "Maaf username atau password salah.");
        }
        return redirect('/user/changeemail')->with('status', "Kami mengirimi anda kode aktivasi. Periksa email anda dan klik tautan untuk memverifikasi.");
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if (isset($verifyUser)) {
            $user = $verifyUser->user;
            if (!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                $status = "Email Anda telah diverifikasi. Anda sekarang dapat masuk.";
            } else {
                $status = "Email Anda sudah diverifikasi. Anda sekarang dapat masuk.";
            }
        } else {
            return redirect('/login')->with('warning', "Maaf email Anda tidak dapat diidentifikasi.");
        }

        return redirect('/login')->with('status', $status);
    }
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
        return redirect('/login')->with('status', 'Kami mengirimi Anda kode aktivasi. Periksa email Anda dan klik tautan untuk memverifikasi.');
    }
}
