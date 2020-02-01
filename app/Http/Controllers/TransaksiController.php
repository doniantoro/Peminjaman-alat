<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Barang;
use App\Transaksi;
use App\Transaksi_barang;
use App\User;
use Auth;
use Session;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->level == 'User') {
            $datas = Transaksi::where('nama', Auth::user()->nama)->get();
        } else {
            $datas = Transaksi::with('transaksi_barang')->get();
        }
        return view('transaksi.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $getRow = Transaksi::orderBy('id', 'DESC')->get();
        $rowCount = $getRow->count();

        $lastId = $getRow->first();

        $kode = "TR00001";

        if ($rowCount > 0) {
            if ($lastId->id < 9) {
                $kode = "TR0000" . '' . ($lastId->id + 1);
            } else if ($lastId->id < 99) {
                $kode = "TR000" . '' . ($lastId->id + 1);
            } else if ($lastId->id < 999) {
                $kode = "TR00" . '' . ($lastId->id + 1);
            } else if ($lastId->id < 9999) {
                $kode = "TR0" . '' . ($lastId->id + 1);
            } else {
                $kode = "TR" . '' . ($lastId->id + 1);
            }
        }
        $barangs = Barang::withCount(['transaksi_barang as sisa_barang' => function ($query) {
            $query->select(DB::raw('jumlah_barang - IFNULL(sum(jumlah_barang_pinjam),0)'))
                ->leftJoin('transaksi', 'transaksi.id', '=', 'transaksi_barang.transaksi_id')
                ->where('status', 'pinjam');
            }])->having('sisa_barang', '>', '0')->get();
        $users = User::get();
        return view('transaksi.create', compact('barangs', 'kode', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->file('file_surat') == '') {
            $file_surat = null;
        } else {
            $file = $request->file('file_surat');
            $dt = Carbon::now();
            $acak = $file->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '-' . $dt->format('Y-m-d-H-i-s') . '.' . $acak;
            $request->file('file_surat')->move("images/transaksi", $fileName);
            $file_surat = $fileName;
        }
        $barang = Barang::get();
        $data = $request->all();
        $datas = $request->all();
        
        $data['status'] = 'pinjam';
        $id = Transaksi::create($data)->id;
        $count = count($_POST['barang_id']);
        $data = array();
        $param="";
        $int=0;
        for ($i = 0; $i < $count; $i++) {
            $same=true;
            // fetch data barang
             foreach($barang as $barangs)
			{
            if (($barangs->jumlah_barang < $request->jumlah_barang_pinjam)&& ($request->barang_id == $barangs->id))
            {
                $same=false; 
                $failed[$int]=$datas['jumlah_barang_pinjam'][$i];	//simpan barang yang dipinjam melebihi stok
                $param=1;   // parameter jika ada barang yang melebihi stok
                $int++;
            }
        }
        if ($same==true){ // if ada barang yang sama
            $param=2;
                $data[] = array(
                    'transaksi_id' => $id,
                    'barang_id' => $request->input('barang_id.' . $i),
                    'jumlah_barang_pinjam' => $request->input('jumlah_barang_pinjam.' . $i),  
                    // 'keterangan_barang' => $request->input('keterangan_barang.' .$i),
                );
            }

        }
        Transaksi_barang::insert($data);

        //cetak alert jika ada yang sama 
        if($param == 1){
			session()->flash('status', 'Maaf jumlah '.$failed[0] .' Tidak dapat di proses,karena sisa barang lebih sedikit dari jumlah barang');		// alihkan halaman ke halaman input
		 }
         else{
            session()->flash('status', 'Data telah ditambahkan!');
    
         }
        return redirect()->route('transaksi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data = Transaksi::with('transaksi_barang')->where('id', $id)->first();

        if ((Auth::User()->level == 'User') && (Auth::User()->anggota->id != $data->anggota_id)) {
            Alert::info('Anda dilarang masuk ke area ini.');
            return redirect()->to('/');

        }

        return view('transaksi.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Transaksi::with('transaksi_barang')->where('id', $id)->first();

        if ((Auth::User()->level == 'user') && (Auth::User()->anggota->id != $data->anggota_id)) {
            Alert::info('Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }

        $data = Transaksi::findOrFail($id);
        $barangs = Barang::withCount(['transaksi_barang as sisa_barang' => function ($query) {
            $query->select(DB::raw('jumlah_barang - IFNULL(sum(jumlah_barang_pinjam),0)'))
                ->leftJoin('transaksi', 'transaksi.id', '=', 'transaksi_barang.transaksi_id')
                ->where('status', 'pinjam');
        }])->having('sisa_barang', '>', '0')->get();
        $users = User::get();
        session()->flash('Berhasil', 'Data Berhasil di update!');
        return view('transaksi.edit', compact('data', 'barangs', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kembali($id)
    {
       $updateTransaksi = DB::table('transaksi')
            ->where('id', '=', $id)
            ->update(array(
                'status' => 'Kembali',
            ));     
            if (count($updateTransaksi) > 0) {
            alert()->success('Berhasil.', 'Data telah dihapus!');
            return redirect()->route('transaksi.index');
        }              
        
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        if ($request->file('file_surat') == '') {
            $file_surat = $transaksi->file_surat;
        } else {
            $file = $request->file('file_surat');
            $dt = Carbon::now();
            $acak = $file->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '-' . $dt->format('Y-m-d-H-i-s') . '.' . $acak;
            $request->file('file_surat')->move("images/transaksi", $fileName);
            $file_surat = $fileName;
        }
        $count = count($_POST['barang_id']);
        $data = array();
        for ($i = 0; $i < $count; $i++) {
            $data = array(
                'barang_id' => $request->input('barang_id.' . $i),
                'jumlah_barang_pinjam' => $request->input('jumlah_barang_pinjam.' . $i),
            );
            Transaksi_barang::where('id', $request->input('id_transaksi_barang.' . $i))->update($data);
        }
        $updateTransaksi = DB::table('transaksi')
            ->where('id', '=', $request->input('id'))
            ->update(array(
                'file_surat' => ($file_surat),
                'tgl_pinjam' => $request->input('tgl_pinjam'),
                'tgl_kembali' => $request->input('tgl_kembali'),
                'nama_peminjam' => $request->input('nama_peminjam'),
                'nim' => $request->input('nim'),
                'no_telp' => $request->input('no_telp'),
                'nama_organisasi' => $request->input('nama_organisasi'),
                'ket' => $request->input('ket'),
                'status' => $request->input('status'),
            ));


        alert()->success('Berhasil.', 'Data barang telah berubah!');
        return redirect()->route('transaksi.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $transaksi = Transaksi::find($id);
        // Helpers::DeleteFile($transaksi->file_surat);

        $transaksi_barang = Transaksi_barang::where('transaksi_id', $id);
        $transaksi_barang->delete();
        $destroy = DB::table('transaksi')
            ->where("id", $id)
            ->delete();

        if (count($destroy) > 0) {
            alert()->success('Berhasil.', 'Data telah dihapus!');
            return redirect()->route('transaksi.index');
        } else {
            alert()->success('Berhasil.', 'Data gagal dihapus!');
            return redirect()->route('transaksi.index');
        }
    }
    public function delete_barang(Request $request)
    {
        $transaksi_barang = Transaksi_barang::where('id', $request->id);
        $transaksi_barang->delete();
        return response()->json(['pesan' => 'Data berhasil dihapus']);
    }
}