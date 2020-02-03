@section('js')
<script type="text/javascript">
$(document).on('click', '.pilih', function (e) {
    var barangOrder = $('#barangOrder').val();
    $('.nama_barang').eq(barangOrder).val($(this).attr('data-nama_barang'))
    $('.barang_id').eq(barangOrder).val($(this).attr('data-barang_id'))
    $('#myModal').modal('hide');
});
$(document).on('click', '.pilih_user', function (e) {
    document.getElementById("nama_organisasi").value = $(this).attr('data-nama');
    $('#myModal2').modal('hide');
});
$(document).on('click', '.pilih2', function (a) {
    document.getElementById("nama_barang2").value = $(this).attr('data-nama_barang2');
    document.getElementById("barang_id2").value = $(this).attr('data-barang_id2');
    $('#myModal3').modal('hide');
});
$(function () {
    $("#lookup, #lookup2, #lookup3").dataTable();
});
function readURL() {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(input).prev().attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(function () {
    $(".uploads").change(readURL)
    $("#f").submit(function(){
        // do ajax submit or just classic form submit
      //  alert("fake subminting")
        return false
    })
})
var check = function() {
  if (document.getElementById('password').value ==
    document.getElementById('confirm_password').value) {
    document.getElementById('submit').disabled = false;
    document.getElementById('message').style.color = 'green';
    document.getElementById('message').innerHTML = 'matching';
  } else {
    document.getElementById('submit').disabled = true;
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'not matching';
  }
}
$(".readonly").on('keydown paste', function(e){
    e.preventDefault();
});
function cloneDataRow(idClone,classToClone) {
    var clone = $('#'+idClone).clone();
    console.log(clone);
    var i = 0;
    var countRow = $('.'+classToClone).length + 1;
    var newIdClone = idClone + countRow;
    clone.find("input").val("");
    clone.find(".hapusBarang").attr({
        onclick: 'removeCloneRow(\''+newIdClone+'\')',
        style: ''
    });
    clone.attr("id", newIdClone).insertAfter('.'+classToClone+':last');
}
function removeCloneRow(idClone) {
    $('#'+idClone).remove();
}
$(document).on('click', '.cariBarang', function(event) {
    $('#barangOrder').val($('.cariBarang').index(this));
    $('#myModal').modal('show');
});
</script>

@stop
@section('css')
@stop
@extends('layouts.app')
@section('content')


<div class="box-body">
    <form method="POST" action="{{ route('transaksi.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
                <div class="row flex-grow">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Tambah Transaksi Baru</h4>
                                <div class="form-group{{ $errors->has('kode_transaksi') ? ' has-error' : '' }}">
                                    <label for="kode_transaksi" class="col-md-4 control-label">Kode Transaksi</label>
                                    <div class="col-md-3">
                                        <input id="kode_transaksi" type="text" class="form-control" name="kode_transaksi" value="{{ $kode }}" required readonly="">
                                        @if ($errors->has('kode_transaksi'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('kode_transaksi') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('tgl_pinjam') ? ' has-error' : '' }}">
                                    <label for="tgl_pinjam" class="col-md-2 control-label">Tanggal Pinjam</label>
                                    <div class="col-md-3">
                                        <input id="tgl_pinjam" type="date" class="form-control" name="tgl_pinjam" value="{{ date('Y-m-d', strtotime(Carbon\Carbon::today()->toDateString())) }}" required @if(Auth::user()->level == 'user') readonly @endif>
                                        @if ($errors->has('tgl_pinjam'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tgl_pinjam') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('tgl_kembali') ? ' has-error' : '' }}">
                                    <label for="tgl_kembali" class="col-md-5 control-label">Tanggal Harus Kembali</label>
                                    <div class="col-md-3">
                                        <input id="tgl_kembali" type="date"  class="form-control" name="tgl_kembali" value="{{ date('Y-m-d', strtotime(Carbon\Carbon::today()->addDays(5)->toDateString())) }}" required="" @if(Auth::user()->level == 'user') readonly @endif>
                                        @if ($errors->has('tgl_kembali'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tgl_kembali') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="file_surat" class="col-md-6 control-label">File Surat</label>
                                    <div class="col-md-3">
                                        <img class="product" width="200" height="200" />
                                        <input type="file" class="uploads form-control" style="margin-top: 20px;" name="file_surat">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-success" onclick="cloneDataRow('rowBarangClone','rowBarangClone')"><i class="fa fa-plus"></i> Tambah Barang</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row rowBarangClone" id="rowBarangClone">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Barang</label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control readonly nama_barang" required>
                                                    <input type="hidden" name="barang_id[]" class="barang_id">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info btn-secondary cariBarang"><b>Cari Barang</b>
                                                        <span class="fa fa-search"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="jumlah_barang_pinjam" class="col-md-8 control-label">Jumlah Barang</label>
                                            <div class="input-group">
                                                <div class="col-sm-8">
                                                    <input type="text" name="jumlah_barang_pinjam[]" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="keterangan_barang" class="col-md-10 control-label">Keterangan Barang</label>
                                            <div class="input-group">
                                                <div class="col-sm-10">
                                                    <input type="text" name="keterangan_barang[]" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="col-md-6 control-label"></label>
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-danger hapusBarang" style="display: none;"><i class="fa fa-minus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(Auth::User()->level == 'admin')
                                <div class="form-group{{ $errors->has('nama_organisasi') ? ' has-error' : '' }}">
                                    <label for="nama_organisasi" class="col-md-4 control-label">Nama Organisasi</label>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <input id="nama_organisasi" class="form-control" type="text" name="nama_organisasi" value="{{ old('nama_organisasi') }}" required readonly="">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-warning btn-secondary" data-toggle="modal" data-target="#myModal2"><b>Cari Organisasi</b> <span class="fa fa-search"></span></button>
                                            </span>
                                        </div>
                                        @if ($errors->has('nama_organisasi'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nama_organisasi') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <div class="form-group{{ $errors->has('nama_organisasi') ? ' has-error' : '' }}">
                                    <label for="nama_organisasi" class="col-md-4 control-label">Nama Organisasi</label>
                                    <div class="col-md-6">
                                        <input id="nama_organisasi" type="text" class="form-control" readonly="" value="{{Auth::User()->user->nama}}" required>
                                        @if ($errors->has('nama_organisasi'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nama_organisasi') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="nama_peminjam" class="col-md-6 control-label">Nama Peminjam</label>
                                    <div class="col-md-5">
                                        <input type="text" name="nama_peminjam" class="form-control" value="{{old('nama_peminjam')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nim" class="col-md-6 control-label">NIM Peminjam</label>
                                    <div class="col-md-5">
                                        <input type="text" name="nim" class="form-control" value="{{old('nim')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="no_telp" class="col-md-6 control-label">No.Telfon Peminjam</label>
                                    <div class="col-md-5">
                                        <input type="text" name="no_telp" class="form-control" value="{{old('no_telp')}}">
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                                    <label for="level" class="col-md-4 control-label">Jenis Kegiatan</label>
                                    <div class="col-md-5">
                                        <select class="form-control" name="ket" required="">
                                            <option value=""></option>
                                            <option value="MAKRAB">Makrab</option>
                                            <option value="LDK">LDK</option>
                                            <option value="KUNJUNGAN">Kunjungan</option>
                                            <option value="SEMINAR">Seminar</option>
                                            <option value="PELATIHAN">Pelatihan</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="submit">
                                Simpan
                                </button>
                                <button type="reset" class="btn btn-danger">
                                Reset
                                </button>
                                <a href="{{route('transaksi.index')}}" class="btn btn-light pull-right">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content" style="background: #fff;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cari Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="lookup2" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Sisa Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangs as $value)
                            <tr class="pilih" data-barang_id="<?php echo $value->id; ?>" data-nama_barang="<?php echo $value->nama_barang; ?>" >
                                <td>{{$value->nama_barang}}</td>
                                <td>{{$value->sisa_barang}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <input type="hidden" id="barangOrder">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content" style="background: #fff;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cari Organisasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="lookup" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Nama Organisasi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $data)
                            <tr class="pilih_user" data-id="<?php echo $data->id; ?>" data-nama="<?php echo $data->nama; ?>" >
                                <td>
                                    {{$data->nama}}
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection