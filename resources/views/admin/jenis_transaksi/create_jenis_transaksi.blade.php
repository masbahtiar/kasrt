@extends('layouts.myapp')
@section('style')
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header with-border">
                <h3>{{ $judul }}</h3>
            </div>

            <form method="POST" class="form-horizontal" action="{{ route('admin.jenistransaksi.store') }}" id="updForm" novalidate>
                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Nama Jenis Transaksi</label>

                        <div class="col-md-8 {{ $errors->has('nama_jtransaksi') ? ' has-error' : '' }}">
                            <input id="nama_jtransaksi" type="text" class="form-control" name="nama_jtransaksi" value="{{ old('nama_jtransaksi') }}" required autofocus>

                            @if ($errors->has('nama_jtransaksi'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nama_jtransaksi') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Akun Debet</label>
                        <div class="col-md-8 {{ $errors->has('akun_debet') ? ' has-error' : '' }}">
                            <select class="form-control" id="qakunDebet" name="akun_debet">
                                @foreach($akun_debets as $key =>$value)
                                <option data-nmsubakun="{{ $value->nmsubakun }}" value="{{ $value->kdsubakun }}" {{old('akun_debet') == $value->kdsubakun?"selected":""}}>{{ $value->nmsubakun }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('akun_debet'))
                            <span class="help-block">
                                <strong>{{ $errors->first('akun_debet') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Akun Kredit</label>
                        <div class="col-md-8 {{ $errors->has('akun_kredit') ? ' has-error' : '' }}">
                            <select class="form-control" id="qakunKredit" name="akun_kredit">
                                @foreach($akun_kredits as $key =>$value)
                                <option data-nmsubakun="{{ $value->nmsubakun }}" value="{{ $value->kdsubakun }}" {{old('akun_kredit') == $value->kdsubakun?"selected":""}}>{{ $value->nmsubakun }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('akun_kredit'))
                            <span class="help-block">
                                <strong>{{ $errors->first('akun_kredit') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Nama Alias</label>

                        <div class="col-md-8 {{ $errors->has('nama_alias') ? ' has-error' : '' }}">
                            <input id="nama_alias" type="text" class="form-control" name="nama_alias" value="{{ old('nama_alias') }}" required autofocus>

                            @if ($errors->has('nama_alias'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nama_alias') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Keterangan</label>

                        <div class="col-md-8 {{ $errors->has('keterangan') ? ' has-error' : '' }}">
                            <input id="keterangan" type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" required autofocus>

                            @if ($errors->has('keterangan'))
                            <span class="help-block">
                                <strong>{{ $errors->first('keterangan') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-info pull-right">Simpan</button>
                        <a href="{{ url('admin/jenistransaksi')}}" class="btn btn-link pull-left">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    var apiUrl = "{{ url('api') }}";

    $(function() {
        $('#qakunDebet').select2();
        $('#qakunKredit').select2();
    });
</script>
@endsection
