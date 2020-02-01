<?php

namespace App\Http\Controllers;

use App\Barang;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use Session;

class BarangController extends Controller
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
        $barang = Barang::withCount(['transaksi_barang as sisa_barang' => function ($query) {
            $query->select(DB::raw('jumlah_barang - IFNULL(sum(jumlah_barang_pinjam),0)'));
        }])->get();
        return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if (Auth::user()->level == 'user') {
            Alert::info('Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }
        alert()->success('status.', 'Data Berhasil Ditambahkan!'); // pesan alert

        return view('barang.create');
    }

    public function format()
    {
        $data = [['nama_barang' => null, 'jumlah_barang' => null, 'sisa_barang' => null]];
        $fileName = 'format-barang';

        $export = Excel::create($fileName . date('Y-m-d_H-i-s'), function ($excel) use ($data) {
            $excel->sheet('barang', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        });

        return $export->download('xlsx');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'importBarang' => 'required',
        ]);

        if ($request->hasFile('importBarang')) {
            $path = $request->file('importBarang')->getRealPath();

            $data = Excel::load($path, function ($reader) {})->get();
            $a = collect($data);

            if (!empty($a) && $a->count()) {
                foreach ($a as $key => $value) {
                    $insert[] = [
                        'nama_barang' => $value->nama_barang,
                        'jumlah_barang' => $value->jumlah_barang,
                        'sisa_barang' => $value->sisa_barang,                        
                    ];

                    Barang::create($insert[$key]);

                }

            };
        }
        alert()->success('status.', 'Data telah diimport!');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_barang' => 'required|string|max:255',
            'jumlah_barang' => 'required|string',
        ]);

        if ($request->file('cover')) {
            $file = $request->file('cover');
            $dt = Carbon::now();
            $acak = $file->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '-' . $dt->format('Y-m-d-H-i-s') . '.' . $acak;
            $request->file('cover')->move("images/barang", $fileName);
            $cover = $fileName;
        } else {
            $cover = null;
        }

        Barang::create([
            'nama_barang' => $request->get('nama_barang'),
            'jumlah_barang' => $request->get('jumlah_barang'),
            'sisa_barang' => $request->get('jumlah_barang'),            
        ]);

        session()->flash('status', 'Data Berhasil di ditambahkan!');

        return redirect()->route('barang.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->level == 'user') {
            Alert::info('Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }

        $data = Barang::findOrFail($id);

        return view('barang.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->level == 'user') {
            Alert::info('Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }
        $data = Barang::findOrFail($id);
        return view('barang.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->file('cover')) {
            $file = $request->file('cover');
            $dt = Carbon::now();
            $acak = $file->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '-' . $dt->format('Y-m-d-H-i-s') . '.' . $acak;
            $request->file('cover')->move("images/barang", $fileName);
            $cover = $fileName;
        } else {
            $cover = null;
        }

        Barang::find($id)->update([
            'nama_barang' => $request->get('nama_barang'),
            'jumlah_barang' => $request->get('jumlah_barang'),
            'sisa_barang' => $request->get('jumlah_barang'),           
        ]);

        session()->flash('status', 'Data Berhasil di update!');
        return redirect()->route('barang.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Barang::find($id)->delete();
        session()->flash('status', 'Data telah dihapus!');
        return redirect()->route('barang.index');
    }
}
