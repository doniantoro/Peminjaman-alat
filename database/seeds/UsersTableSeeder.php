<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       \App\User::insert([
            [
              'id'  			=> 1,
              'nama'  			=> 'Safrie Rizky Abdhandy - Admin',
			  'nim'				=> 'G.211.16.0010',
			  'fakultas'		=> 'Teknologi Informasi dan Komunikasi',
			  'progdi'			=> 'Teknik Informatika',
			  'orma_ukm'		=> 'BEM FTIK',
			  'no_hp'			=> '081221002930',
              'username'		=> 'dhandy123',
              'email' 			=> 'dhandy@gmail.com',
              'password'		=> bcrypt('dhandy123'),
              'gambar'			=> NULL,
              'level'			=> 'admin',
              'remember_token'	=> NULL,
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ],
            [
              'id'  			=> 2,
              'nama'  			=> 'Ade Tegar Febrian - User',
			  'nim'				=> 'G.211.16.0011',
			  'fakultas'		=> 'Teknologi Informasi dan Komunikasi',
			  'progdi'			=> 'Teknik Informatika',
			  'orma_ukm'		=> 'HIMMATISI',
			  'no_hp'			=> '081221002920',
              'username'		=> 'tegar123',
              'email' 			=> 'tegar@gilacoding.com',
              'password'		=> bcrypt('tegar123'),
              'gambar'			=> NULL,
              'level'			=> 'user',
              'remember_token'	=> NULL,
              'created_at'      => \Carbon\Carbon::now(),
              'updated_at'      => \Carbon\Carbon::now()
            ]
        ]);
    }
}
