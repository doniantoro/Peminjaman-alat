<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;
use App\Barang;
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
        $transaksi = Transaksi::get();        
        $barang    = Barang::get();
        if(Auth::User()->level == 'user')
        {
            $datas = Transaksi::where('status', 'Pinjam')
                                ->where('nama_organisasi', Auth::user()->nama)
                                ->get();
        } else {
            $datas = Transaksi::where('status', 'Pinjam')->get();
        }
        return view('home', compact('transaksi', 'barang', 'datas'));
    }
}
