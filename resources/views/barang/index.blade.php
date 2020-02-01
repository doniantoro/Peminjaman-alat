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
@if(Auth::User()->level == 'admin')
  <div class="col-lg-2">
    <a href="{{ route('barang.create') }}" class="btn btn-primary btn-rounded btn-fw"><i class="fa fa-plus"></i> Tambah Barang</a>
  </div>
@endif  
    <div class="col-lg-12">
                  @if (Session::has('message'))
                  <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{{Session::get('message')}}</div>
                  @endif
                  </div>
</div>


@if(session()->has('status'))
 
  <div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button> 
	 {{ session('status') }}
	</div>
@endif

<div class="row" style="margin-top: 20px;">
<div class="col-lg-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title pull-left">Data Barang</h4>
                  <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <thead>
                        <tr>
                          <th>
                            Barang
                          </th>
                         <th>
                            Jumlah Barang / Sisa
                          </th>						                            
						                @if(Auth::User()->level == 'admin')
                          <th>
                            Action
                          </th>
						                @endif
                         </tr>
                      </thead>
                      <tbody>
                      @foreach($barang as $value)
                        <tr>
                          <td class="py-1">
                          <a href="{{route('barang.show', $value->id)}}"> 
                            {{$value->nama_barang}}
                          </a>
                          </td>
                          <td>
                          {{ $value->jumlah_barang }} / {{getSisaBarang($value->id)}}						  
                          </td>
                          <td>
						              @if(Auth::User()->level == 'admin')
                          <div class="btn-group dropdown">
                          <button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                          </button>
                          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                            <a class="dropdown-item" href="{{route('barang.edit', $value->id)}}"> Edit </a>						
                            <form action="{{ route('barang.destroy', $value->id) }}" class="pull-left"  method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="dropdown-item" onclick="return confirm('Anda yakin ingin menghapus data ini?')"> Delete
                            </button>
                          </form>                           
                          </div>
                        </div>
						            @endif
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