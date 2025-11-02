<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <style>
        .drop {
            opacity: 0;
            animation: drop 1s ease-in-out 0.1s forwards;
        }

        @keyframes drop {
            0% {
                opacity: 0;
                transform: translateY(-150%);
            }

            80% {
                opacity: 0.4;
                transform: translateY(30%);
            }

            100% {
                opacity: 1;
                transform: translateY(0%);
            }
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box drop">
        <!-- /.login-logo -->
        <div class="card card-outline card-warning">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>{{ config('app.name', 'DapatForsa') }}</b></a>
            </div>
            <div class="card-body">

                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
                @endif

                <p class="login-box-msg">Sign in untuk masuk aplikasi anda</p>
                <form action="{{ route('login') }}" method="post" novalidate>
                    @csrf
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Username" value="{{ old('email') }}" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('email'))
                        <span class="error help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" name="password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('password'))
                        <span class="error help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-warning btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                        <!-- /.col -->
                    </div>
                </form>
                <p></p>
                <p class="mb-1">
                    <a href="{{ url('user/resendemail') }}">Belum terima email, Kirimkan email ulang</a>
                </p>
                <p class="mb-1">
                    <a href="{{ url('password/reset') }}">Saya lupa password saya</a>
                </p>
                <p class="mb-1">
                    <a href="{{ url('user/changeemail') }}">Ganti Email</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">Daftar</a>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>
