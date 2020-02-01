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

  <div class="col-lg-2">
    <a href="{{ route('anggota.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Tambah Anggota</a>
  </div>
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
                  <h4 class="card-title">Data Anggota</h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <thead>
                        <tr>
                          <th>
                            Nama
                          </th>
                          <th>
                            NIM
                          </th>
                          <th>
                            Progdi
                          </th>
                          <th>
                            Jenis Kelamin
                          </th>
                          <th>
                            Action
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($datas as $data)
                        <tr>
                          <td class="py-1">
                         {{$data->nama}}
                          </td>
                          <td>
                          <a href="{{route('anggota.show', $data->id)}}"> 
                            {{$data->nim}}
                          </a>
                          </td>

                          <td>
                          @if($data->progdi == 'TeknikInformatika')
                            S1 - Teknik Informatika
                          @elseif($data->progdi == 'SistemInformasi')
                            S1 - Sistem Informasi
                          @elseif($data->progdi == 'ILKOM')
                            S1 - Ilmu Komunikasi
                          @elseif($data->progdi == 'Manajemen')							
						  S1 - Manajemen
						  @elseif($data->progdi == 'Akuntansi')							
						  S1 - Akuntansi
						  @elseif($data->progdi == 'Hukum')							
						  S1 - Ilmu Hukum
						  @elseif($data->progdi == 'Sipil')							
						  S1 - Teknik Sipil
						  @elseif($data->progdi == 'Elektro')							
						  S1 - Teknik Elektro
						  @elseif($data->progdi == 'Pertanian')							
						  S1 - Teknik Hasil Pertanian
						  @elseif($data->progdi == 'TPWK')							
						  S1 - Teknik Perancangan Wilayah dan Kota
						  @elseif($data->progdi == 'Psikologi')							
						  S1 - Psikologi
                          @endif
                          </td>
                          <td>
                            {{$data->jk === "L" ? "Laki - Laki" : "Perempuan"}}
                          </td>
                          <td>
                           <div class="btn-group dropdown">
                          <button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                          </button>
                          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                            <a class="dropdown-item" href="{{route('anggota.edit', $data->id)}}"> Edit </a>
                            <form action="{{ route('anggota.destroy', $data->id) }}" class="pull-left"  method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="dropdown-item" onclick="return confirm('Anda yakin ingin menghapus data ini?')"> Delete
                            </button>
                          </form>
                           
                          </div>
                        </div>
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
               {{--  {!! $datas->links() !!} --}}
                </div>
              </div>
            </div>
          </div>
@endsection