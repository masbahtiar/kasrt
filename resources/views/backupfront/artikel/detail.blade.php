<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'E-PROPOSAL') }}</title>

        <!-- Fonts -->
    <link href="{{ asset('css/bootstrap4.min.css') }}" media="screen" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/font-awesome.min.css') }}" media="screen" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap4.min.js') }}"></script>

        <!-- Styles -->
        <style>
        body {
  padding-top: 54px;
}

@media (min-width: 992px) {
  body {
    padding-top: 56px;
  }
}
        </style>
    </head>
    <body>
<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="{{url('/')}}">E-PROPOSAL</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="{{url('/')}}">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/')}}/{{Auth::user()->roles()->first()->role_name}}">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/logout') }}">Logout</a>
                        </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    @endauth
            @endif
            </li>
          </ul>
        </div>
      </div>
    </nav>

 <!-- Page Content -->
    <div class="container">

      <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-12">

          <h1 class="my-4">{{ strtoupper($artikel->kategori()->first()->nm_kategori)}}
          </h1>

          <!-- Blog Post -->
        <div class="col-md-12">
          <div class="card mb-4">
            <div class="card-body">
              <h2 class="card-title">{{ $artikel->judul }}</h2>
              <p class="card-text">
                {!! $artikel->isi_artikel !!}
              </p>
            </div>
            <div class="card-footer text-muted">
              Posted on {{ date('d F Y H:i:s', strtotime($artikel->updated_at)) }}, by {{$artikel->user()->first()->name}}
            </div>
           </div>
          </div>
        </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; elomba.net 2018</p>
      </div>
      <!-- /.container -->
    </footer>

    </body>
</html>
