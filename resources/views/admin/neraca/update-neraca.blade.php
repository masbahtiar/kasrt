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

            <form method="POST" class="form-horizontal" action="{{ route('admin.saveneraca') }}" id="updForm" novalidate>
                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Tahun Buku</label>
                        <div class="col-md-8 {{ $errors->has('year') ? ' has-error' : '' }}">
                            <select class="form-control" id="qyear" name="year">
                                @foreach($years as $key =>$value)
                                <option value="{{ $value }}">{{$value}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('year'))
                            <span class="help-block">
                                <strong>{{ $errors->first('year') }}</strong>
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
        $('#qyear').select2();
    });
</script>
@endsection
