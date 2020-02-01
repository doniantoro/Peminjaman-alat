<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Barang;
use App\Transaksi;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;
use Session;

class LaporanController extends Controller
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

    public function brg()
    {
        return view('laporan.barang');
    }

    public function brgPdf()
    {

        $datas = Barang::withCount(['transaksi_barang as sisa_barang' => function ($query) {
            $query->select(DB::raw('jumlah_barang - IFNULL(sum(jumlah_barang_pinjam),0)'));
        }])->get();
        $pdf = PDF::loadView('laporan.barang_pdf', compact('datas'));
        return $pdf->download('laporan_barang_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function brgExcel(Request $request)
    {
        $nama = 'laporan_barang_' . date('Y-m-d_H-i-s');
        Excel::create($nama, function ($excel) use ($request) {
            $excel->sheet('Laporan Data Barang', function ($sheet) use ($request) {

                $sheet->mergeCells('A1:H1');

                // $sheet->setAllBorders('thin');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(11);
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                });

                $sheet->row(1, array('LAPORAN DATA BARANG'));

                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(11);
                    $row->setFontWeight('bold');
                });

                $datas = Barang::withCount(['transaksi_barang as sisa_barang' => function ($query) {
                    $query->select(DB::raw('jumlah_barang - IFNULL(sum(jumlah_barang_pinjam),0)'));
                }])->get();

                // $sheet->appendRow(array_keys($datas[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                });

                $datasheet = array();
                $datasheet[0] = array("NO", "NAMA BARANG", "JUMLAH BARANG", "SISA BARANG");
                $i = 1;

                foreach ($datas as $data) {

                    // $sheet->appendrow($data);
                    $datasheet[$i] = array($i,
                        $data['nama_barang'],
                        $data['jumlah_barang'],
                        $data['sisa_barang'],
                    );

                    $i++;
                }

                $sheet->fromArray($datasheet);
            });

        })->export('xls');

    }

    public function transaksi()
    {
        return view('laporan.transaksi');
    }

    public function transaksiPdf(Request $request)
    {
        $q = Transaksi::with('transaksi_barang');

        if ($request->get('status')) {
            if ($request->get('status') == 'Pinjam') {
                $q->where('status', 'Pinjam');
            } else {
                $q->where('status', 'Kembali');
            }
        }

        if (Auth::User()->level == 'user') {
            $q->where('nama_organisasi', Auth::User()->nama);
        }

        $datas = $q->get();

        // return view('laporan.transaksi_pdf', compact('datas'));
        $pdf = PDF::loadView('laporan.transaksi_pdf', compact('datas'));
        return $pdf->download('laporan_transaksi_' . $request->get('status') . '_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function transaksiExcel(Request $request)
    {

        $nama = 'laporan_transaksi_' . date('Y-m-d_H-i-s');
        Excel::create($nama, function ($excel) use ($request) {
            $excel->sheet('Laporan Data Transaksi', function ($sheet) use ($request) {

                $sheet->mergeCells('A1:H1');

                // $sheet->setAllBorders('thin');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(11);
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                });

                $sheet->row(1, array('LAPORAN DATA TRANSAKSI'));

                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(11);
                    $row->setFontWeight('bold');
                });

                $q = Transaksi::with('transaksi_barang');

                if ($request->get('status')) {
                    if ($request->get('status') == 'Pinjam') {
                        $q->where('status', 'Pinjam');
                    } else {
                        $q->where('status', 'Kembali');
                    }
                }

                if (Auth::User()->level == 'user') {
                    $q->where('nama_organisasi', Auth::User()->nama);
                }

                $datas = $q->get();

                // $sheet->appendRow(array_keys($datas[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                });

                $datasheet = array();
                $datasheet[0] = array("NO", "KODE TRANSAKSI", "NAMA BARANG", "JUMLAH BARANG","NAMA PEMINJAM", "TGL PINJAM", "TGL KEMBALI", "STATUS", "KET");
                $i = 1;
                // return($data['transaksi_barang']);
                foreach ($datas as $data) {

                    // $sheet->appendrow($data);
                    $datasheet[$i] = array($i,
                        $data['kode_transaksi'],                                            
                        getLaporanExcel($data['transaksi_barang']),
                        getExcel($data['transaksi_barang']),
                        $data['nama_peminjam'],
                        date('d/m/y', strtotime($data['tgl_pinjam'])),
                        date('d/m/y', strtotime($data['tgl_kembali'])),
                        $data['status'],
                        $data['ket'],
                    );

                    $i++;
                }

                $sheet->fromArray($datasheet);
            });

        })->export('xls');

    }
}
