<?php

use Illuminate\Database\Seeder;

class AnggotasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Anggota::insert([
            [
              'id'  			=> 1,
              'anggota_id'  	=> 1,
              'nim'				=> 'G.211.16.0010',
              'nama' 			=> 'Safrie Rizky Abdhandy',
              'tempat_lahir'	=> 'Demak',
              'tgl_lahir'		=> '2018-01-01',
              'jk'				=> 'L',
              'prodi'			=> 'TI',
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ],
            [
              'id'  			=> 2,
              'anggota_id'  	=> 2,
              'nim'				=> 'G.211.16.0011',
              'nama' 			=> 'Ade Tegar Febrian',
              'tempat_lahir'	=> 'demak',
              'tgl_lahir'		=> '2019-01-01',
              'jk'				=> 'L',
              'prodi'			=> 'TI',
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ],
        ]);
    }
}
