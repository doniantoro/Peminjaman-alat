@section('js')
<script type="text/javascript">
$(document).on('click', '.pilih', function(e) {
    var barangOrder = $('#barangOrder').val();
    $('.nama_barang').eq(barangOrder).val($(this).attr('data-nama_barang'))
    $('.barang_id').eq(barangOrder).val($(this).attr('data-barang_id'))
    $('#myModal').modal('hide');
});
$(document).on('click', '.pilih_user', function(e) {
    document.getElementById("nama_organisasi").value = $(this).attr('data-nama');
    $('#myModal2').modal('hide');
});
$(document).on('click', '.pilih2', function(a) {
    document.getElementById("nama_barang2").value = $(this).attr('data-nama_barang2');
    document.getElementById("barang_id2").value = $(this).attr('data-barang_id2');
    $('#myModal3').modal('hide');
});
$(function() {
    $("#lookup, #lookup2, #lookup3").dataTable();
});
function readURL() {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(input).prev().attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(function() {
    $(".uploads").change(readURL)
    $("#f").submit(function() {
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
$(".readonly").on('keydown paste', function(e) {
    e.preventDefault();
});
function cloneDataRow(idClone, classToClone) {
    var clone = $('#' + idClone).clone();
    console.log(clone);
    var i = 0;
    var countRow = $('.' + classToClone).length + 1;
    var newIdClone = idClone + countRow;
    clone.find("input").val("");
    clone.find(".hapusBarang").attr({
        onclick: 'removeCloneRow(\'' + newIdClone + '\')',
        style: ''
    });
    clone.attr("id", newIdClone).insertAfter('.' + classToClone + ':last');
}
function removeCloneRow(idClone) {
    $('#' + idClone).remove();
}
$(document).on('click', '.cariBarang', function(event) {
    $('#barangOrder').val($('.cariBarang').index(this));
    $('#myModal').modal('show');
});
function deleteBarang(idRow = '', id = '') {
    if (confirm('Apakah anda yakin akan menghapus data ini..')) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        // ajax delete data to database
        $.ajax({
            url: "{{ url('/transaksi/delete_barang') }}",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function(data) {
                //if success reload ajax table
                alert(data.pesan);
                $('#'+idRow).remove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });
    }
}
</script>
@stop

@extends('layouts.app')
@section('content')



<form action="{{ route('transaksi.update', $data->id) }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Transaksi</h4>
                            <input type="hidden" name="id" value="{!! $data->id !!}">
                            <div class="form-group">
                                <label for="kode_transaksi" class="col-md-4 control-label">Kode Transaksi</label>
                                <div class="col-md-6">
                                    <input id="kode_transaksi" type="text" class="form-control" value="{{ $data->kode_transaksi }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tgl_pinjam" class="col-md-4 control-label">Tanggal Pinjam</label>
                                <div class="col-md-3">
                                    <input id="tgl_pinjam" type="date" class="form-control" name="tgl_pinjam" value="{{ date('Y-m-d', strtotime(Carbon\Carbon::today()->toDateString())) }}" required
                                    @if(Auth::user()->level == 'user')
                                    readonly
                                    @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tgl_kembali" class="col-md-4 control-label">Tanggal Kembali</label>
                                <div class="col-md-3">
                                    <input id="tgl_kembali" type="date"  class="form-control" name="tgl_kembali" value="{{ date('Y-m-d', strtotime(Carbon\Carbon::today()->addDays(5)->toDateString())) }}" required=""
                                    @if(Auth::user()->level == 'user')
                                    readonly
                                    @endif>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="file_surat" class="col-md-4 control-label">File Surat</label>
                                <div class="col-md-6">
                                    <img class="product" width="200" height="200" @if($data->file_surat) src="{{ asset('images/transaksi/'.$data->file_surat) }}" @endif />
                                    <input type="file" class="uploads form-control" style="margin-top: 20px;" name="file_surat">
                                </div>
                            </div>
                            @foreach ($data->transaksi_barang as $value)
                            <div class="row rowBarangClone" id="rowBarangClone{{ $value->id }}">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">Barang</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control readonly nama_barang" required value="{!! getBarang($value->barang_id) !!}">
                                                <input type="hidden" name="barang_id[]" class="barang_id" value="{!! $value->barang_id !!}">
                                                <input type="hidden" name="id_transaksi_barang[]" value="{!! $value->id !!}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info btn-secondary cariBarang"><b>Cari Barang</b>
                                                    <span class="fa fa-search"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="jumlah_barang_pinjam" class="col-md-6 control-label">Jumlah Barang</label>
                                        <div class="input-group">
                                            <div class="col-sm-12">
                                                <input type="text" name="jumlah_barang_pinjam[]" class="form-control" required value="{!! $value->jumlah_barang_pinjam !!}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="=col-md-6   ">
                                    <div class="form-group">
                                        <label for="keterangan_barang" class="col-md-10 control-label">Keterangan Barang</label>
                                        <div class="col-md-7">
                                            <input type="text" name="keterangan_barang" class="form-control" value="{!! $value->keterangan_barang !!}">
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="col-md-6 control-label"></label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-danger" onclick="deleteBarang('rowBarangClone{{ $value->id }}',{{ $value->id }})"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if(Auth::User()->level == 'admin')
                            <div class="form-group">
                                <label for="nama_organisasi" class="col-md-4 control-label">Nama Organisasi</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="nama_organisasi" type="text" class="form-control" name="nama_organisasi" value="{{ $data->nama_organisasi }}" required readonly="">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-warning btn-secondary" data-toggle="modal" data-target="#myModal2"><b>Cari Organisasi</b> <span class="fa fa-search"></span></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="nama_organisasi" class="col-md-4 control-label">Nama Organisasi</label>
                                <div class="col-md-6">
                                    <input id="nama_organisasi" type="text" class="form-control" readonly="" value="{{$data->nama_organisasi }}" required>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="ket" class="col-md-4 control-label">Jenis Kegiatan</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="ket" required="">
                                        <option value=""></option>
                                        <option value="MAKRAB"{{$data->ket === "Makrab" ? "selected" : ""}} >Makrab</option>
                                        <option value="LDK"{{$data->ket === "LDK" ? "selected" : ""}} >LDK</option>
                                        <option value="KUNJUNGAN"{{$data->ket === "Kunjungan" ? "selected" : ""}} >Kunjungan</option>
                                        <option value="SEMINAR"{{$data->ket === "Seminar" ? "selected" : ""}} >Seminar</option>
                                        <option value="PELATIHAN"{{$data->ket === "Pelatihan" ? "selected" : ""}} >Pelatihan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama_peminjam" class="col-md-6 control-label">Nama Peminjam</label>
                                <div class="col-md-5">
                                    <input type="text" name="nama_peminjam" class="form-control" value="{{$data->nama_peminjam}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nim" class="col-md-6 control-label">NIM Peminjam</label>
                                <div class="col-md-5">
                                    <input type="text" name="nim" class="form-control" value="{{$data->nim}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="no_telp" class="col-md-6 control-label">No.Telfon Peminjam</label>
                                <div class="col-md-5">
                                    <input type="text" name="no_telp" class="form-control" value="{{$data->no_telp}}">
                                </div>
                            </div>
                            @if (Auth::User()->level == 'admin')
                            <div class="form-group">
                                <label for="status" class="col-md-4 control-label">Status</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="status" required="">
                                        <option value=""></option>
                                        <option value="Pinjam"{{$data->status === "Pinjam" ? "selected" : ""}} >Pinjam</option>
                                        <option value="Kembali"{{$data->status === "Kembali" ? "selected" : ""}} >Kembali</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</tr>
@endsection

