
@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable({
      "iDisplayLength": 50
    });

} );
</script>
@stop
@extends('layouts.app')

@section('content')
@if(session()->has('status'))
 
  <div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button> 
	<strong> {{ session('status') }}</strong>
	</div>
@endif

<div class="row">
  @if(Auth::User()->level == 'admin')
  <div class="col-lg-2">
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Tambah Transaksi</a>
  </div>
  @endif
  <div class="col-lg-12">
    @if (Session::has('message'))
    <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{{ Session::get('message') }}</div>
    @endif
  </div>
</div>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        @if(Auth::User()->level == 'admin')
        <h4 class="card-title">Data Transaksi</h4>
        @endif
        <h4 class="card-title">Data Peminjaman</h4>
        <div class="table-responsive">
          <table class="table table-striped" id="table">
            <thead>
              <tr>
                <th>
                  Kode
                </th>
                <th>
                  Nama Organisasi
                </th>
                <th>
                  Rincian Peminjam
                </th>
                <th>
                  Pinjam Barang || Sisa Barang
                </th>
                <th>
                  Tgl Pinjam
                </th>
                <th>
                  Tgl Harus Kembali
                </th>
                <th>
                  Jenis Kegiatan
                </th>
                <th>
                  Status
                </th>
                @if(Auth::User()->level == 'admin')
                <th>
                  Action
                </th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($datas as $data)
              <tr>
                <td class="py-1">
                  <a href="{{route('transaksi.show', $data->id)}}">
                    {{$data->kode_transaksi}}
                  </a>
                </td>
                <td>
                  {{$data->nama_organisasi}}
                </td>
                <td>
                  Nama Peminjam : {{$data->nama_peminjam}}
                  </br><br>
                  NIM : {{$data->nim}}
                  </br><br>
                  No. Telpon :{{$data->no_telp}}
                </td>
                <td>
                  <ul>
								@foreach($data->transaksi_barang as $t)
                  <li>{{$t['barang']->nama_barang}} : Jumlah {{$t->jumlah_barang_pinjam}} || Sisa {{$t['barang']->sisa_barang}}</li>

        				@endforeach
                  </ul>
                </td>
                <td>
                  {{date('d/m/y', strtotime($data->tgl_pinjam))}}
                </td>
                <td>
                  {{date('d/m/y', strtotime($data->tgl_kembali))}}
                </td>
                <td>
                  @if($data->ket == 'Makrab')
                  Makrab
                  @elseif($data->ket == 'Pelatihan')
                  Pelatihan
                  @elseif($data->ket == 'LDK')
                  LDK
                  @elseif($data->ket == 'Kunjungan')
                  Kunjungan
                  @elseif($data->ket == 'Seminar')
                  Seminar
                  @endif
                </td>
                <td>
                  @if($data->status == 'Pinjam')
                  <label class="badge badge-warning">Pinjam</label>
                  @else
                  <label class="badge badge-success">Kembali</label>
                  @endif
                </td>
                <td>
                  @if(Auth::User()->level == 'admin')
                  <div class="btn-group dropdown">
                    <button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    @endif
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">                    
                      <form action="{{ route('transaksi.destroy', $data->id) }}" class="pull-left"  method="post">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="dropdown-item" onclick="return confirm('Anda yakin ingin menghapus data ini?')"> Delete
                        </button>
                      </form>
                      @if($data->status == 'Pinjam')
                      <a class="dropdown-item" href="{{route('transaksi.edit', $data->id)}}"> Edit </a>
                      @endif
                    </div>
                  </div>                  
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
