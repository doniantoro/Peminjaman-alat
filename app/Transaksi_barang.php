<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi_barang extends Model
{
    protected $table = 'transaksi_barang';
    protected $fillable = ['transaksi_id', 'barang_id', 'jumlah_barang_pinjam', 'keterangan_barang'];

    public function transaksi()
    {
        return $this->belongsTo('App\Transaksi');
    }
    public function barang()
    {
        return $this->hasOne('App\Barang','id','barang_id');
    }
}
