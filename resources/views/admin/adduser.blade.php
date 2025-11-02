@extends('layouts.myapp')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/typeahead/typeahead.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3>Tambah Pengguna</h3>
                </div>

                <form method="POST" class="form-horizontal" action="{{ route('admin.adduser') }}" id="addForm">
                    <div class="card-body">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 control-label">{{ __('Name') }}</label>

                            <div class="col-md-8 {{ $errors->has('name') ? ' has-error' : '' }}">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

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
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
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
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

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
                                    <option value="{{$role->role_name}}">{{ucwords($role->role_name)}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 control-label">{{ __('Password') }}</label>

                            <div class="col-md-8 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 control-label">{{ __('Confirm Password') }}</label>

                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sekolah_id" class="col-md-4 control-label">Sekolah</label>
                            <div class="col-md-8 {{ $errors->has('sekolah_id') ? ' has-error' : '' }}">
                                <input id="srcSekolah" name="srcSekolah" class="form-control typeahead" type="text" placeholder="Sekolah">
                                <input type="hidden" id="sekolah_id" name="sekolah_id" value="">
                                @if ($errors->has('sekolah_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sekolah_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <a href="{{ url('admin/users')}}" class="btn btn-link pull-left">Kembali</a>
                        <button type="submit" class="btn btn-info pull-right">
                            {{ __('Register') }}
                    </div>

            </div>
            </form>
        </div>
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
        $("#addForm").validate({
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
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
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
                email: "Masukkan Email yang benar",
                password: {
                    required: "Masukkan Password",
                    minlength: "Password Minimal 6 karakter"
                },
                password_confirmation: {
                    required: "Masukkan Password",
                    minlength: "Password minimal 6 karakter",
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

    })
</script>
@endsection
