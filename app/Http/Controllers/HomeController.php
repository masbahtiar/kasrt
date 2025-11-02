<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->roles()->first()->role_name == 'admin') { // do your margic here
            return redirect()->route('admin');
        }
        if (Auth::user()->roles()->first()->role_name == 'anggota') { // do your margic here
            return redirect()->route('sekolah');
        }
        if (Auth::user()->roles()->first()->role_name == 'pengurus') { // do your margic here
            return redirect()->route('verifikasi');
        } else {
            return view('home');
        }
    }
}
