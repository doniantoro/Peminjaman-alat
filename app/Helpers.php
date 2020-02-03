<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of helpers
 *
 * @author Herzi
 */
function setActive($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array) $path) ? $active : '';
}
/*
function alert($message = null, $title = '') {
return call_user_func_array('Alert::', $path.';
}
 */
function setShow($path, $show = 'show')
{
    return call_user_func_array('Request::is', (array) $path) ? $show : '';
}

function formatDate($array)
{
    $string = date('Y-m-d', strtotime($array));
    return $string;
}

function getBarang($id)
{
    $rs = \DB::table('barang')
        ->select('nama_barang')
        ->where('id', '=', $id)
        ->first();

    if ($rs) {
        $barang = $rs->nama_barang;
    } else {
        $barang = "id barang tidak valid";
    }

    return $barang;
}

function getLaporanExcel($data)
{
    $brg = '';
    foreach ($data as $value) {
        $rp = \DB::table('barang')
        ->select('nama_barang')
        ->where('id', '=', $value->barang_id)
        ->first();
        $brg .= $rp->nama_barang.',';
    }
    return $brg;
    // if (count($rp) > 0) {
    //     $transaksi_barang = $rp->barang_id;
    // } else {
    //     $transaksi_barang = "id barang tidak valid";
    // }

    return $transaksi_barang;
}

function getExcel($data)
{
    $jml ='';
    foreach ($data as $value) {
        $rs = \DB::table('transaksi_barang')
        ->select('jumlah_barang_pinjam')
        ->where('barang_id', '=', $value->barang_id)
        ->first();   
        $jml .= $rs->jumlah_barang_pinjam.',';     

    }
    return $jml;        

    // if (count($rs) > 0) {
    //     $transaksi_barang = $rs->jumlah_barang_pinjam;
    // } else {
    //     $transaksi_barang = "jumlah barang tidak valid";
    //        }    
     
    return $transaksi_barang;
}

function getSisaBarang($id)
{
    // $rs = \DB::table('barang')
    //     ->selectRaw('(select jumlah_barang - IFNULL(sum(jumlah_barang_pinjam),0) from transaksi_barang where barang.id = transaksi_barang.barang_id) AS sisa_barang')
    //     ->join('transaksi_barang','barang.id','=','transaksi_barang.barang_id')
    //     ->join('transaksi','transaksi_barang.transaksi_id','=','transaksi.id')
    //     ->where('barang.id', '=', $id)
    //     ->where('transaksi.status','=','pinjam')
    //     ->first();

    // if ($rs) {
    //     $barang = $rs->sisa_barang;
    // } else {
    //     $rs = \DB::table('barang')
    //     ->where('barang.id', '=', $id)
    //     ->first();
    //     $barang = $rs->jumlah_barang;
    // }

    // return $barang;
}

function getAnggota($id)
{
    $rs = \DB::table('anggota')
        ->select('nama')
        ->where('id', '=', $id)
        ->first();

    if (count($rs) > 0) {
        $anggota = $rs->nama;
    } else {
        $anggota = "id anggota tidak valid";
    }

    return $anggota;
}

function DeleteFile($name)
{
    $origin = public_path("images/transaksi/" . $name);
    if (file_exists($origin)) {
        File::delete($origin);
    }
}

if (!function_exists('num_row')) {
    function num_row($page, $limit)
    {
        if (is_null($page)) {
            $page = 1;
        }

        $num = ($page * $limit) - ($limit - 1);
        return $num;
    }
}
