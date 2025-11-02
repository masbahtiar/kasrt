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

            <form method="POST" class="form-horizontal" action="{{ route('admin.akun.store') }}" id="updForm" novalidate>
                <div class="card-body">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Jenis</label>

                        <div class="col-md-8 {{ $errors->has('kdjenis') ? ' has-error' : '' }}">
                            <input id="jenis" type="hidden" class="form-control" name="jenis" value="{{ old('jenis') }}" required>
                            <select class="form-control" id="qkdjenis" name="kdjenis">
                                @foreach($jenises as $key =>$value)
                                <option data-jenis="{{ $value->jenis }}" value="{{ $value->kdjenis }}" {{old('kdjenis') == $value->kdjenis?"selected":""}}>{{ $value->jenis }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('kdjenis'))
                            <span class="help-block">
                                <strong>{{ $errors->first('kdjenis') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Kode Akun</label>
                        <div class="col-md-8 {{ $errors->has('kdakun') ? ' has-error' : '' }}">
                            <input id="nmakun" type="hidden" class="form-control" name="nmakun" value="{{ old('nmakun') }}" required>
                            <select class="form-control" id="qkdakun" name="kdakun">
                                @foreach($nmakuns as $key =>$value)
                                <option data-nmakun="{{ $value->nmakun }}" value="{{ $value->kdakun }}" {{old('kdakun') == $value->kdakun?"selected":""}}>{{ $value->nmakun }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('kdakun'))
                            <span class="help-block">
                                <strong>{{ $errors->first('kdakun') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Kode Sub Akun</label>
                        <div class="col-md-8 {{ $errors->has('subakun') ? ' has-error' : '' }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="lbsubakun">{{old('kdakun')}}</span>
                                </div>
                                <input type="hidden" name="kdsubakun" id="kdsubakun" value="{{ old('kdsubakun') }}">
                                <input id="subakun" type="text" aria-label="Default" aria-describedby="inputGroup-sizing-md" class="form-control" name="subakun" value="{{ old('subakun') }}" required autofocus>
                            </div>
                            <div class="d-flex flex-column">
                                @if ($errors->has('subakun'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subakun') }}</strong>
                                </span>
                                @endif
                                @if ($errors->has('kdsubakun'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('kdsubakun') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Nama Sub Akun</label>

                        <div class="col-md-8 {{ $errors->has('nmsubakun') ? ' has-error' : '' }}">
                            <input id="nmsubakun" type="text" class="form-control" name="nmsubakun" value="{{ old('nmsubakun') }}" required autofocus>

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
<script>
    var apiUrl = "{{ url('api') }}";
    var getNamaAkun = (kdakun) => {
        $.ajax({
                url: apiUrl + `/akun/getnamaakun/${kdakun}`,
                type: 'GET',
                dataType: 'json'
            })
            .done(function(data) {
                var nmakuns = data.data;
                $('#qkdakun').empty();
                var opt = "";
                nmakuns.forEach((nmakun, i) => {
                    opt += `<option data-nmakun='${nmakun.nmakun}' value='${nmakun.kdakun}' >${nmakun.nmakun}</option>`;
                });
                $('#qkdakun').append(opt);
                $('#qkdakun').trigger('change')
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
    }
    var getKodeAkun = (kdakun) => {
        $('#subakun').val('')
        $.ajax({
                url: apiUrl + `/akun/getkodeakun/${kdakun}`,
                type: 'GET',
                dataType: 'json'
            })
            .done(function(data) {
                var subakun = data.data;
                $('#subakun').val(subakun)
                $('#kdsubakun').val(`${ kdakun}${subakun}`);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
    }

    $(function() {
        $('#lbsubakun').text($('#qkdakun').val());
        getKodeAkun($('#qkdakun').val())

        $('#qkdjenis').select2();
        $('#qkdakun').select2();
        $('#qkdjenis').change(function() {
            var jenis = $('option:selected', this).attr('data-jenis');
            $('#jenis').val(jenis);
            getNamaAkun($('option:selected', this).val());
        })
        $('#qkdakun').change(function() {
            var nmakun = $('option:selected', this).attr('data-nmakun');
            let kdakun = $('option:selected', this).val();
            $('#nmakun').val(nmakun);
            $('#lbsubakun').text(kdakun);
            getKodeAkun(kdakun)
        })
        $('#subakun').blur(function() {
            var subakun = $('#subakun').val();
            if (subakun !== '') {
                $('#kdsubakun').val(`${ $('#qkdakun').val()}${subakun}`);
            } else {
                $('#kdsubakun').val('');
            }
        })

    });
</script>
@endsection
