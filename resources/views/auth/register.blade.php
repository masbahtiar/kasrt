<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'DapatForsa') }} || Daftar Pengguna Baru</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/typeahead/typeahead.css') }}">

</head>

<body class="hold-transition register-page">
    <div class="register-box">

        <div class="card card-outline card-primary">

            <div class="card-header text-center">
                <a href="{{ url('/')}}" class=" h1"><b>DAPATFORSA</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Pendaftaran Pengguna Baru</p>
                <form method="POST" action="{{ route('register') }}" id="frmRegister">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="input-group mb-2 {{ $errors->has('username') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" placeholder="Username" name="username" id="username" value="{{ old('username') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">

                        <div class="input-group mb-2 {{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-2 {{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" class="form-control" placeholder="password" name="password" value="{{ old('password') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-2 {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <input type="password" class="form-control" placeholder="Password Confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    Saya setuju <a href="#">ketentuan diatas</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" id="register-btn" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div> <!-- /.col -->
            </div>
            </form>
        </div>
        <!-- /.form-box -->
    </div>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('plugins/typeahead/bloodhound.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/typeahead/typeahead.jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/typeahead/typeahead.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/handlebars-v4.0.11.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app/register.js')}}"></script>
</body>

</html>
