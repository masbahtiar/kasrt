@extends('layouts.myapp')
@section('style')
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header with-border">
                <h3>Update Setting Aplikasi</h3>
            </div>
            <div class="card-body">
                <form method="POST" class="form-horizontal" action="{{ route('admin.updsetting') }}" id="updForm">
                    @csrf
                    <input id="id" type="hidden" class="form-control" name="id" value="{{$setting->id}}" required autofocus>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">ID</label>
                        <div class="col-md-8 {{ $errors->has('id') ? ' has-error' : '' }}">
                            <div class="form-control-static">{{$setting->id}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Nilai</label>

                        <div class="col-md-8 {{ $errors->has('nilai') ? ' has-error' : '' }}">
                            <input id="nilai" type="text" class="form-control" name="nilai" value="{{ $setting->nilai }}" required autofocus>

                            @if ($errors->has('nilai'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nilai') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Keterangan</label>

                        <div class="col-md-8 {{ $errors->has('keterangan') ? ' has-error' : '' }}">
                            <input id="keterangan" type="text" class="form-control" name="keterangan" value="{{ $setting->keterangan }}" required autofocus>

                            @if ($errors->has('keterangan'))
                            <span class="help-block">
                                <strong>{{ $errors->first('keterangan') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-info pull-right">Simpan</button>
                            <a href="{{ url('admin/setting')}}" class="btn btn-link pull-left">Kembali</a>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection
