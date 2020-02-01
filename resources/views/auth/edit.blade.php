@section('js')
    <script type="text/javascript">
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
    </script>
@stop

@extends('layouts.app')

@section('content')

<form action="{{ route('user.update', $data->id) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('put') }}
<div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
              <div class="row flex-grow">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Edit user</h4>
                      
                        <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }}">
                            <label for="nama" class="col-md-4 control-label">Nama</label>
                            <div class="col-md-6">
                                <input id="nama" type="text" class="form-control" name="nama" value="{{ $data->nama }}" autofocus>
                                @if ($errors->has('nama'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                            <label for="level" class="col-md-4 control-label">Fakultas</label>
                            <div class="col-md-6">
                            <select class="form-control" name="fakultas" >
                                <option value=""></option>
								<option value="FakultasHukum" {{$data->fakultas === "FakultasHukum" ? "selected" : ""}}>Fakultas Hukum</option>
                                <option value="FakultasEkonomi" {{$data->fakultas === "FakultasEkonomi" ? "selected" : ""}}>Fakultas Ekonomi</option>
								<option value="FakultasTeknik" {{$data->fakultas === "FakultasTeknik" ? "selected" : ""}}>Fakultas Teknik</option>
                                <option value="FakultasTeknikPertanian" {{$data->fakultas === "FakultasTeknikPertanian" ? "selected" : ""}}>Fakultas Teknik Pertanian</option>
								<option value="FakultasPsikologi" {{$data->fakultas === "FakultasPsikologi" ? "selected" : ""}}>Fakultas Psikologi</option>
                                <option value="FakultasTIK" {{$data->fakultas === "FakultasTIK" ? "selected" : ""}}>Fakultas Teknologi Informasi dan Komunikasi</option>
								</select>
                            </div>
                        </div>
						
						<div class="form-group{{ $errors->has('orma_ukm') ? ' has-error' : '' }}">
                            <label for="orma_ukm" class="col-md-4 control-label">Orma / UKM</label>
                            <div class="col-md-6">
                            <select class="form-control" name="orma_ukm">
                                <option value=""></option>
                                <option value="UKM">Unit Kegiatan Mahasiswa</option>
                                <option value="BEM">Badan Eksekutif Mahasiswa</option>
                                <option value="DEMA">Dewan Mahasiswa</option>
                                <option value="HMJ">Himupunan Mahasiswa Jurusan</option>
                            </select>
                            </div>
                        </div>
						
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ $data->username }}" required readonly="">
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $data->email }}" required readonly="">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Gambar</label>
                            <div class="col-md-6">
                                <img class="product" width="200" height="200" @if($data->gambar) src="{{ asset('images/user/'.$data->gambar) }}" @endif />
                                <input type="file" class="uploads form-control" style="margin-top: 20px;" name="gambar">
                            </div>
                        </div>
                        @if(Auth::user()->level == 'admin')
                         <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                            <label for="level" class="col-md-4 control-label">Level</label>
                            <div class="col-md-6">
                            <select class="form-control" name="level" required="">
                                <option value="admin" @if($data->level == 'admin') selected @endif>Admin</option>
                                <option value="user" @if($data->level == 'user') selected @endif>User</option>
                            </select>
                            </div>
                        </div>
                        @endif


                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" onkeyup='check();' name="password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="confirm_password" type="password" onkeyup="check()" class="form-control" name="password_confirmation">
                                <span id='message'></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit">
                                    Update
                        </button>
                        <a href="{{route('user.index')}}" class="btn btn-light pull-right">Back</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

</div>
</form>
@endsection