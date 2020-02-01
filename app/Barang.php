<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'jumlah_barang'];

    /**
     * Method One To Many
     */
    public function transaksi_barang()
    {
        return $this->hasMany(Transaksi_barang::class);
    }
}
