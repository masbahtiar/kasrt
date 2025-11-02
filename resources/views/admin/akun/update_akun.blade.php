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

            <form method="POST" class="form-horizontal" action="{{ route('admin.akun.update') }}" id="updForm">
                <div class="card-body">
                    @csrf

                    <input id="id" type="hidden" class="form-control" name="id" value="{{$akun->id}}" required>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Jenis</label>
                        <div class="col-md-8 {{ $errors->has('kdjenis') ? ' has-error' : '' }}">
                            <input type="hidden" name="kdjenis" value="{{ $akun->kdjenis }}" required>
                            <input type="hidden" name="jenis" value="{{ $akun->jenis }}" required>
                            <div class="form-control-static">{{$akun->jenis}}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Nama Akun</label>
                        <div class="col-md-8 {{ $errors->has('kdjenis') ? ' has-error' : '' }}">
                            <input type="hidden" name="kdakun" value="{{ $akun->kdakun }}">
                            <input type="hidden" name="nmakun" value="{{ $akun->nmakun }}">
                            <div class="form-control-static">{{$akun->nmakun}}</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Sub Akun</label>
                        <div class="col-md-8 {{ $errors->has('nmsubakun') ? ' has-error' : '' }}">
                            <input type="hidden" name="kdsubakun" value="{{ $akun->kdsubakun }}">
                            <input id="nmsubakun" type="text" class="form-control" name="nmsubakun" value="{{ $akun->nmsubakun }}" required autofocus>
                            @if ($errors->has('nmsubakun'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nmsubakun') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info pull-right">Simpan</button>
                        <a href="{{ url('admin/akun')}}" class="btn btn-link pull-left">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
