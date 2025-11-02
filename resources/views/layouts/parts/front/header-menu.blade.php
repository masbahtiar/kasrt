<nav class="classy-navbar justify-content-between" id="magNav">

    <!-- Nav brand -->
    <a href="{{url('/')}}" class="nav-brand"><img src="{{ asset('front/img/core-img/logo.png') }}" alt=""></a>

    <!-- Navbar Toggler -->
    <div class="classy-navbar-toggler">
        <span class="navbarToggler"><span></span><span></span><span></span></span>
    </div>

    <!-- Nav Content -->
    <div class="nav-content d-flex align-items-center">
        <div class="classy-menu">
            <!-- Close Button -->
            <div class="classycloseIcon">
                <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
            </div>

            <!-- Nav Start -->
            <div class="classynav">
                <ul>
                    <li class="active"><a href="{{url('/')}}">Home</a></li>
                    <li><a href="#">Pengumuman</a>
                        <ul class="dropdown">
                            @foreach ( $pengumuman as $lts)
                            <li style="overflow: auto;">
                                <a href="{{url('/artikel')}}/{{$lts->id}}">
                                    {{ strtoupper(substr($lts->judul,0,50)) }}
                                </a>

                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="#">Profil</a>
                        <ul class="dropdown">
                            @foreach ( $profil as $lts)
                            <li><a href="{{url('/artikel')}}/{{$lts->id}}"> {{ strtoupper(substr($lts->judul,0,50)) }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- Nav End -->
        </div>

        <div class="top-meta-data d-flex align-items-center">
            @if (Route::has('login'))
            @auth
            <a href="{{ route('logout') }}" class="login-btn"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
            <a href="{{url('/')}}/{{Auth::user()->roles()->first()->role_name}}" class="submit-video"><span><i class="fa fa-gears"></i></span> <span class="video-text">Admin</span></a>
            @else
            <a href="{{ route('login') }}" class="login-btn"><i class="fa fa-user" aria-hidden="true"></i></a>
            @endauth
            @endif
        </div>
    </div>
</nav>
