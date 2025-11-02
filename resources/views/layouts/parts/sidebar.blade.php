<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'KASRT') }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a class="nav-link my-0" href="{{ url('/') }}" class="d-block">{{ auth()->user()->name}}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Sidebar Menu -->
                @if(auth()->user()->hasRole('admin'))
                <!-- Admin -->
                <li class="nav-header">MENU ADMIN</li>
                <li class="nav-item"><a class="nav-link" href="{{url('admin')}}"><i class="fas fa-th-large nav-icon"></i><span>
                            <p>Dashboard</p>
                        </span></a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#" class="nav-link active">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Utility
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/users') }}">
                                <i class="fas fa-user-cog nav-icon"></i>
                                <p>Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/setting') }}">
                                <i class="fas fa-tools nav-icon"></i>
                                <p>Pengaturan Sistem</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/flashmessage') }}">
                                <i class="fas fa-envelope nav-icon"></i>
                                <p>Flash Message</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Kelola Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/akun') }}">
                                <i class="fas fa-book nav-icon"></i>
                                <p>Akun</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/jenistransaksi') }}">
                                <i class="fas fa-play-circle nav-icon"></i>
                                <p>Jenis Transaksi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="nav-icon fas fa-plus-square"></i>
                        <p>
                            Transaksi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a class="nav-link" href="{{ url('admin/jurnal') }}"><i class="fas fa-graduation-cap nav-icon"></i>
                                <p>Posting Jurnal</p>
                            </a></li>
                        <li><a class="nav-link" href="{{ url('admin/jimpitan') }}"><i class="fas fa-th-large nav-icon"></i>
                                <p>Jimpitan</p>
                            </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" class="nav-link active">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview text-sm (0.875rem)">
                        <li class="nav-item"><a class="nav-link" href="{{ url('laporan/bukubesar') }}"><i class="fas fa-circle nav-icon"></i>
                                <p>Buku Besar</p>
                            </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('laporan/rekapjimpitan') }}"><i class="fas fa-circle nav-icon"></i>
                                <p>Rekap Jimpitan</p>
                            </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('laporan/neraca') }}"><i class="fas fa-circle nav-icon"></i>
                                <p>Neraca</p>
                            </a></li>
                    </ul>
                </li>
                <!-- End Admin -->
                @elseif(auth()->user()->hasRole('anggota'))
                <!-- Verifikasi -->
                <li class="nav-header">MENU ANGGOTA</li>
                <ul class="nav nav-treeview text-sm (0.875rem)">
                    <li class="nav-item">
                        <a class="nav-link" href="#" class="nav-link active">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Laporan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview text-sm (0.875rem)">
                            <li class="nav-item"><a class="nav-link" href="{{ url('laporan/bukubesar') }}"><i class="fas fa-circle nav-icon"></i>
                                    <p>Buku Besar</p>
                                </a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('laporan/rekapjimpitan') }}"><i class="fas fa-circle nav-icon"></i>
                                    <p>Rekap Jimpitan</p>
                                </a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('laporan/neraca') }}"><i class="fas fa-circle nav-icon"></i>
                                    <p>Neraca</p>
                                </a></li>
                        </ul>
                    </li>

                </ul>
                <!-- End Verifikasi -->
                @else
                <!-- Sekolah -->
                <li class="nav-header">MENU PENGURUS</li>
                <li class="nav-item">
                    <a class="nav-link" href="#" class="nav-link active">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview text-sm (0.875rem)">
                        <li class="nav-item"><a class="nav-link" href="{{ url('laporan/bukubesar') }}"><i class="fas fa-circle nav-icon"></i>
                                <p>Buku Besar</p>
                            </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('laporan/rekapjimpitan') }}"><i class="fas fa-circle nav-icon"></i>
                                <p>Rekap Jimpitan</p>
                            </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('laporan/neraca') }}"><i class="fas fa-circle nav-icon"></i>
                                <p>Neraca</p>
                            </a></li>
                    </ul>
                </li>
            </ul>
            </li>
            <!-- End Sekolah -->
            @endif
            </ul>
        </nav>
    </div>
    <!-- /.sidebar-menu -->
    <!-- /.sidebar -->
</aside>
