@extends('layouts.myapp-verifikasi')

@section('style')
<link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">

@endsection
@section('content')

<!-- =========================================================== -->
<div class="row">
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
