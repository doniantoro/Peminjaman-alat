<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
/*
Route::get('/', function () {
return view('home');
});
 */
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');
/*
Route::get('/user', 'UserController@index');
Route::get('/user-register', 'UserController@create');
Route::post('/user-register', 'UserController@store');
Route::get('/user-edit/{id}', 'UserController@edit');
 */
Route::resource('user', 'UserController');

Route::resource('anggota', 'AnggotaController');

Route::resource('barang', 'BarangController');
Route::get('/format_barang', 'BarangController@format');
Route::post('/import_barang', 'BarangController@import');

Route::resource('transaksi', 'TransaksiController');
Route::post('transaksi/delete_barang', 'TransaksiController@delete_barang');
Route::get('kembali/{id}', 'TransaksiController@kembali');
Route::get('/laporan/trs', 'LaporanController@transaksi');
Route::get('/laporan/trs/pdf', 'LaporanController@transaksiPdf');
Route::get('/laporan/trs/excel', 'LaporanController@transaksiExcel');

Route::get('/laporan/brg', 'LaporanController@brg');
Route::get('/laporan/brg/pdf', 'LaporanController@brgPdf');
Route::get('/laporan/brg/excel', 'LaporanController@brgExcel');
