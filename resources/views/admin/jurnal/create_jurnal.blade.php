@extends('layouts.myapp')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/typeahead/typeahead.css') }}">
<style>
    #image-preview {
        display: none;
        width: 300px;
        height: auto;
        -webkit-box-shadow: 0 10px 6px -6px #777;
        -moz-box-shadow: 0 10px 6px -6px #777;
        box-shadow: 0 10px 6px -6px #777;
    }
</style>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header with-border">
                <h3>{{ $judul }}</h3>
            </div>

            <form method="POST" class="form-horizontal" action="{{ route('admin.jurnal.store') }}" id="updForm" novalidate enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">No Ref</label>
                        <div class="col-md-8 {{ $errors->has('no_ref') ? ' has-error' : '' }}">
                            <input id="no_ref" type="text" class="form-control" name="no_ref" value="{{ old('no_ref') }}" required autofocus>

                            @if ($errors->has('no_ref'))
                            <span class="help-block">
                                <strong>{{ $errors->first('no_ref') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 control-label">Tanggal Transaksi</label>
                        <div class="col-md-8">
                            <div class="input-group date" id="tanggal_jurnal" data-target-input="nearest">
                                <input name="tanggal_jurnal" type="text" class="form-control datetimepicker-input" data-target="#tanggal_jurnal" />
                                <div class="input-group-append" data-target="#tanggal_jurnal" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Keterangan</label>

                        <div class="col-md-8 {{ $errors->has('ket_jurnal') ? ' has-error' : '' }}">
                            <input id="ket_jurnal" type="text" class="form-control" name="ket_jurnal" value="{{ old('ket_jurnal') }}" required autofocus>

                            @if ($errors->has('ket_jurnal'))
                            <span class="help-block">
                                <strong>{{ $errors->first('ket_jurnal') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Jenis Transaksi</label>
                        <div class="col-md-8 {{ $errors->has('jenis_transaksi_id') ? ' has-error' : '' }}">
                            <select class="form-control" id="qJenisTransaksi" name="jenis_transaksi_id">
                                @foreach($jenis_transaksis as $key =>$value)
                                <option data-nmsubakun="{{ $value->nama_jtransaksi }}" value="{{ $value->id }}" {{old('jenis_transaksi_id') == $value->id?"selected":""}}>{{ $value->nama_jtransaksi }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('jenis_transaksi_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('jenis_transaksi_id') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Jumlah</label>

                        <div class="col-md-8 {{ $errors->has('jumlah') ? ' has-error' : '' }}">
                            <input id="jumlah" type="text" class="form-control" name="jumlah" value="{{ old('jumlah') }}" required autofocus>

                            @if ($errors->has('jumlah'))
                            <span class="help-block">
                                <strong>{{ $errors->first('jumlah') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-md-4 control-label">Gambar</label>
                        <div class="custom-file col-md-4 col-sm-8">
                            <input type="file" name="image_url" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Pilih File</label>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <img src="" alt="" class="image-fluid" id="image-preview">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info pull-right">Simpan</button>
                    <a href="{{ url('admin/jurnal')}}" class="btn btn-link pull-left">Kembali</a>
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
        $('#qJenisTransaksi').select2();
        bsCustomFileInput.init();
        $('#tanggal_jurnal').datetimepicker({
            format: 'DD/MM/yyyy HH:mm:ss',
            locale: moment.locale(),
            defaultDate: moment('{{ date("Y-m-d H:i:s") }}'),
        })

    });
    $('#customFile').change(e => {
        document.getElementById("image-preview").style.display = "block";
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("customFile").files[0]);
        oFReader.onload = function(oFREvent) {
            document.getElementById("image-preview").src = oFREvent.target.result;
        };

    })
</script>
@endsection
