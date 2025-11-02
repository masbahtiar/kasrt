@extends('layouts.myapp')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/typeahead/typeahead.css') }}">
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header with-border">
                <h3>Update Pengguna</h3>
            </div>

            <form method="POST" id="updForm" class="form-horizontal" action="{{ route('admin.upduser') }}">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">{{ __('Name') }}</label>

                        <div class="col-md-8 {{ $errors->has('name') ? ' has-error' : '' }}">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-md-4 control-label">Username</label>
                        <div class="col-md-8 {{ $errors->has('username') ? ' has-error' : '' }}">
                            <input id="username" type="text" class="form-control" name="username" value="{{ $user->username }}" required>
                            @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 control-label">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-8 {{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="roles" class="col-md-4 control-label">Level</label>

                        <div class="col-md-8">
                            <select class="form-control" name="level">
                                @foreach ($roles as $i => $role)
                                <option value="{{$role->role_name}}" {{ ($role->role_name == $user->roles()->first()->role_name)?'selected':'' }}>{{ucwords($role->role_name)}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
                @if($user->hasRole('sekolah'))
                <div class="form-group row">
                    <label for="sekolah_id" class="col-md-4 control-label">Sekolah</label>
                    <div class="col-md-8 {{ $errors->has('sekolah_id') ? ' has-error' : '' }}">
                        <input id="srcSekolah" name="srcSekolah" class="form-control typeahead" type="text" placeholder="Sekolah" value="{{ $user->sekolahs()->count()>0?$user->sekolahs()->first()->nm_sekolah:'' }}">
                        <input type="hidden" id="sekolah_id" name="sekolah_id" value="{{ $user->sekolahs()->count()>0?$user->sekolahs()->first()->id:'' }}">
                        @if ($errors->has('sekolah_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('sekolah_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                @else
                <input type="hidden" name="sekolah_id" value="1">
                @endif

                <div class="card-body">
                    <a href="{{ url('admin/users')}}" class="btn btn-link pull-left">Kembali</a>
                    <button type="submit" class="btn btn-info pull-right">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header with-border">
                <h3>Ubah Password Pengguna</h3>
            </div>
            <form method="POST" id="updPwd" class="form-horizontal" action="{{ route('admin.updpassword') }}">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">

                    <div class="form-group">
                        <label class="col-md-4 control-label">Password Baru</label>
                        <div class="col-md-8">
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Konfirmasi Password Baru</label>
                        <div class="col-md-8">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="btn-updpwd" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset('plugins/typeahead/bloodhound.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/typeahead/typeahead.jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/typeahead/typeahead.bundle.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/handlebars-v4.0.11.js')}}"></script>
<script type="text/javascript">
    var jsUrl = "{{asset('admin/sekolah/qrsekolah')}}";
</script>
<script type="text/javascript" src="{{asset('js/app/updsekolah.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#updForm").validate({
            rules: {
                name: "required",
                username: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                srcSekolah: {
                    required: true
                }
            },
            messages: {
                name: "Masukkan nama user",
                username: {
                    required: "Masukkan Username",
                    minlength: "Minimal 2 karakter"
                },
                email: "Masukkan Email yang benar"
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents(".col-md-8").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".col-md-8").addClass("has-success").removeClass("has-error");
            }

        });

        $("#updPwd").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                },
            },
            messages: {
                password: {
                    required: "Masukkan password baru",
                    minlength: "Minimal 6 karakter"
                },
                password_confirmation: {
                    required: "Konfirmasi password baru",
                    minlength: "Minimal 6 karakter",
                    equalTo: "Password harus sama"
                }
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents(".col-md-8").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".col-md-8").addClass("has-success").removeClass("has-error");
            }

        });


    });
</script>
@endsection
