<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/login';
    protected function authenticated(Request $request, $user)
    {
        if (!$user->verified) {
            Auth::logout();
            return back()->with('warning', 'Anda perlu mengkonfirmasi akun Anda. Kami telah mengirimkan kode aktivasi, silakan periksa email Anda.');
        }
        // return redirect()->intended($this->redirectPath());

        if ($user->roles()->first()->role_name == 'admin') { // do your margic here
            return redirect()->route('admin');
        }
        if ($user->roles()->first()->role_name == 'anggota') { // do your margic here
            return redirect()->route('anggota');
        }
        if ($user->roles()->first()->role_name == 'pengurus') { // do your margic here
            return redirect()->route('pengurus');
        } else {
            return redirect('/login');
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'username';

        return [
            $field => $request->get($this->username()),
            'password' => $request->password,
        ];
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
