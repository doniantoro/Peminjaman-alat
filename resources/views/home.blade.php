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
<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-poll-box text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Transaksi</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$transaksi->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Total Seluruh Transaksi
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-receipt text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Sedang Pinjam</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$transaksi->where('status', 'Pinjam')->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Sedang Dipinjam
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-book text-success icon-lg" style="width: 40px;height: 40px;"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Barang</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$barang->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-book mr-1" aria-hidden="true"></i> Total Barang
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="mdi mdi-account-location text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Sudah Kembali</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">{{$transaksi->where('status', 'Kembali')->count()}}</h3>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-account mr-1" aria-hidden="true"></i> Barang Sudah Dikembalikan
                  </p>
                </div>
              </div>
            </div>
</div>
<div class="row" >
<div class="col-lg-12 grid-margin stretch-card">
              <div class="card">				
                <div class="card-body">
                  <h4 class="card-title">Info Data Pinjam</h4>                  
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
                            Barang Dipinjam || Sisa Barang
                          </th>
                          <!-- <th>
                          Keterangan Barang
                          </th> -->                                                  						              
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
                              Nama Peminjam   :<br>{{$data->nama_peminjam}}</br>
                            <br>
                              NIM       :<br>{{$data->nim}}</br>
                            </br>
                              No. Telpon    :<br>{{$data->no_telp}}</br>
                            </td>
                            <td>
                                <ul>
                                  @foreach($data->transaksi_barang as $value)
                                  <li>{{getBarang($value->barang_id)}} : Jumlah {{$value->jumlah_barang_pinjam}} / Barang Sisa {{getSisaBarang($value->barang_id)}}</li>
                                  @endforeach
                                </ul>
                            </td>
                          <!-- <td>
                            <ul>
                            @foreach($data->transaksi_barang as $value)
                              <li>{{getBarang($value->barang_id)}} : {{$value->keterangan_barang}}</li>
                            @endforeach
                            </ul>
                          </td> -->
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
                              <a  href="{{ url('kembali/'.$data->id) }}" class="btn btn-info btn-sm" type="submit" onclick="return confirm('Anda yakin sudah mengembalikan?')">Sudah Kembali
                              </a>
						          @endif                          
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
