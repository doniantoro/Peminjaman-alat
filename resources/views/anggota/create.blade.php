@section('js')

<script type="text/javascript">

$(document).ready(function() {
    $(".users").select2();
});

</script>
@stop

@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('anggota.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
<div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
              <div class="row flex-grow">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Tambah Anggota Baru</h4>
                      
                        <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                            <label for="nama" class="col-md-4 control-label">Nama</label>
                            <div class="col-md-6">
                                <input id="nama" type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
                                @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }}">
                            <label for="nim" class="col-md-4 control-label">NIM</label>
                            <div class="col-md-6">
                                <input id="nim" type="text" class="form-control" name="nim" value="{{ old('nim') }}" maxlength="8" required>
                                @if ($errors->has('nim'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nim') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('tempat_lahir') ? ' has-error' : '' }}">
                            <label for="tempat_lahir" class="col-md-4 control-label">Tempat Lahir</label>
                            <div class="col-md-6">
                                <input id="tempat_lahir" type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                @if ($errors->has('tempat_lahir'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tempat_lahir') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tgl_lahir') ? ' has-error' : '' }}">
                            <label for="tgl_lahir" class="col-md-4 control-label">Tanggal Lahir</label>
                            <div class="col-md-6">
                                <input id="tgl_lahir" type="date" class="form-control" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required>
                                @if ($errors->has('tgl_lahir'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tgl_lahir') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                            <label for="level" class="col-md-4 control-label">Jenis Kelamin</label>
                            <div class="col-md-6">
                            <select class="form-control" name="jk" required="">
                                <option value=""></option>
                                <option value="L">Laki - Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('progdi') ? ' has-error' : '' }}">
                            <label for="progdi" class="col-md-4 control-label">Progdi</label>
                            <div class="col-md-6">
                            <select class="form-control" name="progdi" required="">
                                <option value=""></option>
                                <option value="S1 - Ilmu Hukum">S1 - Ilmu Hukum</option>
								<option value="DIII - Manajemen Perusahaan">DIII - Manajemen Perusahaan</option>
								<option value="S1 - Manajemen">S1 - Manajemen</option>
								<option value="S1 - Akuntansi">S1 - Akuntansi</option>
								<option value="S1 - Teknik Sipil">S1 - Teknik Sipil</option>
								<option value="S1 - Teknik Elektro">S1 - Teknik Elektro</option>
								<option value="S1 - Teknik Perancangan Wilayah dan Kota">S1 - Teknik Perancangan Wilayah dan Kota</option>
								<option value="S1 - Teknik Hasil Pertanian">S1 - Teknik Hasil Pertanian</option>
								<option value="S1 - Psikologi">S1 - Psikologi</option>
								<option value="S1 - Sistem Informasi">S1 - Sistem Informasi</option>
								<option value="S1 - Teknik Informatika">S1 - Teknik Informatika</option>
                                <option value="S1 - Ilmu Komunikasi">S1 - Ilmu Komunikasi</option>
                            </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('anggota_id') ? ' has-error' : '' }} " style="margin-bottom: 20px;">
                            <label for="anggota_id" class="col-md-4 control-label">User Login</label>
                            <div class="col-md-6">
                            <select class="form-control" name="anggota_id" required="">
                                <option value="">Cari User</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->nama}}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit">
                                    Submit
                        </button>
                        <button type="reset" class="btn btn-danger">
                                    Reset
                        </button>
                        <a href="{{route('anggota.index')}}" class="btn btn-light pull-right">Back</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

</div>
</form>
@endsection