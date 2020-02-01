<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['kode_transaksi', 'nama_organisasi', 'nama_peminjam', 'nim', 'no_telp', 'tgl_pinjam', 'tgl_kembali', 'status', 'file_surat', 'ket'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
    public function transaksi_barang()
    {
        return $this->hasMany(Transaksi_barang::class);
    }
}
