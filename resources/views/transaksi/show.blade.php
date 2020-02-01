@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 d-flex align-items-stretch grid-margin">
        <div class="row flex-grow">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Detail <b>{{$data->kode_transaksi}}</b></h4>
                        <div class="form-group"> </div>
                        <div class="form-group{{ $errors->has('kode_transaksi') ? ' has-error' : '' }}">
                            <label for="kode_transaksi" class="col-md-4 control-label">Kode Transaksi</label>
                            <div class="col-md-3">
                                <input id="kode_transaksi" type="text" class="form-control" name="kode_transaksi" value="{{$data->kode_transaksi}}" required readonly="">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('nama_organisasi') ? ' has-error' : '' }}">
                            <label for="nama_organisasi" class="col-md-4 control-label">Nama Organisasi</label>
                            <div class="col-md-3">
                                <input id="nama_organisasi" type="text" class="form-control" name="nama_organisasi" value="{{$data->nama_organisasi}}" required readonly="">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('nama_peminjam') ? ' has-error' : '' }}">
                            <label for="nama_peminjam" class="col-md-4 control-label">Nama Peminjam</label>
                            <div class="col-md-3">
                                <input id="nama_peminjam" type="text" class="form-control" name="nama_organisasi" value="{{$data->nama_peminjam}}" required readonly="">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }}">
                            <label for="nim" class="col-md-4 control-label">NIM Peminjam</label>
                            <div class="col-md-3">
                                <input id="nim" type="text" class="form-control" name="nim" value="{{$data->nim}}" required readonly="">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
                            <label for="no_telp" class="col-md-4 control-label">No. Telfon Peminjam</label>
                            <div class="col-md-3">
                                <input id="no_telp" type="text" class="form-control" name="no_telp" value="{{$data->no_telp}}" required readonly="">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('tgl_pinjam') ? ' has-error' : '' }}">
                            <label for="tgl_pinjam" class="col-md-4 control-label">Tgl Pinjam</label>
                            <div class="col-md-3">
                                <input id="tgl_pinjam" type="date" class="form-control" name="tgl_pinjam" value="{{ date('Y-m-d', strtotime($data->tgl_pinjam)) }}" readonly="">
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('tgl_kembali') ? ' has-error' : '' }}">
                            <label for="tgl_kembali" class="col-md-4 control-label">Tgl Harus Kembali</label>
                            <div class="col-md-3">
                                <input id="tgl_kembali" type="date"  class="form-control" name="tgl_kembali" value="{{ date('Y-m-d', strtotime($data->tgl_kembali)) }}" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="col-md-4 control-label">File Surat</label>
                            <div class="col-md-3">
                                <img class="product" width="200" height="200" @if($data->file_surat) src="{{ asset('images/transaksi/'.$data->file_surat) }}" @endif />
                            </div>
                        </div>
                        @foreach ($data->transaksi_barang as $value)
                        <div class="row rowBarangClone" id="rowBarangClone">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Barang</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control nama_barang" disabled value="{{ getBarang($value->barang_id) }}">
                                            <input type="hidden" name="barang_id[]" class="barang_id">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah_barang_pinjam" class="col-md-10 control-label">Jumlah Barang</label>
                                    <div class="input-group">
                                        <div class="col-sm-10">
                                            <input type="text" name="jumlah_barang_pinjam[]" class="form-control" disabled value="{{ $value->jumlah_barang_pinjam }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="keterangan_barang" class="col-md-10 control-label">Keterangan Barang</label>
                                    <div class="input-group">
                                        <div class="col-sm-10">
                                            <input type="text" name="keterangan_barang[]" class="form-control" disabled value="{{ $value->keterangan_barang }}">
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        @endforeach
                        <div class="form-group{{ $errors->has('ket') ? ' has-error' : '' }}">
                            <label for="ket" class="col-md-4 control-label">Status</label>
                            <div class="col-md-6">
                                @if($data->status == 'Pinjam')
                                <label class="badge badge-warning">Pinjam</label>
                                @else
                                <label class="badge badge-success">Kembali</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('ket') ? ' has-error' : '' }}">
                            <label for="ket" class="col-md-4 control-label">Jenis Kegiatan</label>
                            <div class="col-md-3">
                                <input id="ket" type="text" class="form-control" name="ket" value="{{ $data->ket }}" readonly="">
                            </div>
                        </div>
                        <a href="{{route('transaksi.index')}}" class="btn btn-light pull-right">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
