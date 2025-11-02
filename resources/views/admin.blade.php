@extends('layouts.myapp')
@section('style')
<link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">

@endsection
@section('content')
<div class="row">
    <div class="col-md-6 col-sm-3">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Utility</h3>
            </div>
            <div class="card-body">
                <p>Pengaturan Sistem dan Pengguna</p>
                <a class="btn btn-app" href="{{ url('admin/users') }}">
                    <i class="fa fa-users-cog"></i> Pengguna
                </a>
                <a class="btn btn-app" href="{{ url('admin/setting') }}">
                    <i class="fa fa-tools"></i> Pengaturan Sistem
                </a>
                <a class="btn btn-app" href="{{ url('admin/flashmessage') }}">
                    <i class="fa fa-envelope"></i> Flash Message
                </a>
            </div><!-- /.card-body -->
        </div>
    </div>
    <div class="col-md-6 col-sm-3">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Kelola Data</h3>
            </div>
            <div class="card-body">
                <p>Mengelola Data </p>
                <a class="btn btn-app" href="{{ url('admin/akun') }}">
                    <i class="fa fa-book"></i> Akun
                </a>
                <a class="btn btn-app" href="{{ url('admin/jenistransaksi') }}">
                    <i class="fa fa-book"></i> Jenis Transaksi
                </a>
            </div><!-- /.card-body -->
        </div>
    </div>
    <div class="col-md-6 col-sm-3">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Transaksi</h3>
            </div>
            <div class="card-body">
                <p>Transaksi</p>
                <a class="btn btn-app" href="{{ url('admin/jurnal') }}">
                    <i class="fa fa-university"></i> Posting Jurnal
                </a>
                <a class="btn btn-app" href="{{ url('admin/jimpitan') }}">
                    <i class="fa fa-home"></i> Iuran Jimpitan
                </a>
                <a class="btn btn-app" href="{{ url('admin/neraca/update') }}">
                    <i class="fa fa-home"></i> Update Neraca
                </a>
            </div><!-- /.card-body -->
        </div>
    </div>
    <div class="col-md-6 col-sm-3">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Laporan</h3>
            </div>
            <div class="card-body">
                <p>Informasi </p>
                <a class="btn btn-app" href="{{ url('laporan/rekapjimpitan') }}">
                    <i class="fa fa-university"></i> Rekap Jimpitan
                </a>
                <a class="btn btn-app" href="{{ url('laporan/bukubesar') }}">
                    <i class="fa fa-home"></i> Buku Besar
                </a>
                <a class="btn btn-app" href="{{ url('laporan/neraca') }}">
                    <i class="fa fa-home"></i> Neraca
                </a>

            </div><!-- /.card-body -->
        </div>
    </div>
</div>
@include('layouts.parts.modal-flash')
@endsection

@section('script')
<script type="text/javascript">
    getFlashMessage();
</script>
@endsection
