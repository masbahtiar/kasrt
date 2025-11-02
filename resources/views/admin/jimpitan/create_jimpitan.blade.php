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

            <form method="POST" class="form-horizontal" action="{{ route('admin.jimpitan.store') }}" id="updForm" novalidate enctype="multipart/form-data">
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
                            <div class="input-group date" id="tanggal_jimpitan" data-target-input="nearest">
                                <input name="tanggal_jimpitan" type="text" class="form-control datetimepicker-input" data-target="#tanggal_jimpitan" />
                                <div class="input-group-append" data-target="#tanggal_jimpitan" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Keterangan</label>

                        <div class="col-md-8 {{ $errors->has('ket_jimpitan') ? ' has-error' : '' }}">
                            <input id="ket_jimpitan" type="text" class="form-control" name="ket_jimpitan" value="{{ old('ket_jimpitan') }}" required autofocus>

                            @if ($errors->has('ket_jimpitan'))
                            <span class="help-block">
                                <strong>{{ $errors->first('ket_jimpitan') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Tahun</label>
                        <div class="col-md-8 {{ $errors->has('tahun') ? ' has-error' : '' }}">
                            <select class="form-control" id="qTahun" name="tahun">
                                @foreach($tahun_opt as $key =>$value)
                                <option value="{{ $value }}" {{date('Y') == $value?"selected":""}}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('tahun'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tahun') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Bulan</label>
                        <div class="col-md-8 {{ $errors->has('bulan') ? ' has-error' : '' }}">
                            <select class="form-control" id="qBulan" name="bulan">
                                @foreach($bulan_opt as $key =>$value)
                                <option value="{{ $key }}" {{date('m') == $key?"selected":""}}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('bulan'))
                            <span class="help-block">
                                <strong>{{ $errors->first('bulan') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Periode</label>
                        <div class="col-md-8 {{ $errors->has('periode') ? ' has-error' : '' }}">
                            <select name="periode" class="custom-select" id="periode">
                                <option value="1" {{ $periode =="1"?"selected":"" }}>1</option>
                                <option value="2" {{ $periode =="2"?"selected":"" }}>2</option>
                                <option value="3" {{ $periode =="3"?"selected":"" }}>3</option>
                                <option value="4" {{ $periode =="4"?"selected":"" }}>4</option>
                                <option value="5" {{ $periode =="5"?"selected":"" }}>5</option>
                            </select>
                            @if ($errors->has('periode'))
                            <span class="help-block">
                                <strong>{{ $errors->first('periode') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Nominal</label>

                        <div class="col-md-8 {{ $errors->has('nominal') ? ' has-error' : '' }}">
                            <input id="nominal" type="hidden" class="form-control" name="nominal" value="{{ $nominal }}">
                            <div class="form-control-static">{{ number_format($nominal) }}</div>

                            @if ($errors->has('nominal'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nominal') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Jumlah Peserta</label>

                        <div class="col-md-8 {{ $errors->has('jumlah_peserta') ? ' has-error' : '' }}">
                            <input id="jumlah_peserta" type="text" class="form-control" name="jumlah_peserta" value="{{ $jumlah_peserta }}" required autofocus>

                            @if ($errors->has('jumlah_peserta'))
                            <span class="help-block">
                                <strong>{{ $errors->first('jumlah_peserta') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Upah</label>
                        <div class="col-md-8 {{ $errors->has('upah') ? ' has-error' : '' }}">
                            <select class="form-control" id="qUpah" name="upah">
                                @foreach($upah_opt as $key =>$value)
                                <option value="{{ $value }}" {{old('upah') == $key?"selected":""}}>{{ number_format($value) }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('upah'))
                            <span class="help-block">
                                <strong>{{ $errors->first('upah') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Sub Total</label>

                        <div class="col-md-8 {{ $errors->has('sub_total') ? ' has-error' : '' }}">
                            <input id="sub_total" type="hidden" class="form-control" name="sub_total" value="{{ $sub_total }}">
                            <div id="div_sub_total" class="form-control-static">{{ number_format($sub_total) }}</div>

                            @if ($errors->has('sub_total'))
                            <span class="help-block">
                                <strong>{{ $errors->first('sub_total') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 control-label">Jumlah Terima</label>

                        <div class="col-md-8 {{ $errors->has('jumlah_terima') ? ' has-error' : '' }}">
                            <input id="jumlah_terima" type="hidden" class="form-control" name="jumlah_terima" value="{{ $jumlah_terima }}">
                            <div id="div_jumlah_terima" class="form-control-static">{{ number_format($jumlah_terima) }}</div>
                            @if ($errors->has('jumlah_terima'))
                            <span class="help-block">
                                <strong>{{ $errors->first('jumlah_terima') }}</strong>
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
                    <a href="{{ url('admin/jimpitan')}}" class="btn btn-link pull-left">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    var apiUrl = "{{ url('api') }}";

    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
        return val;
    }

    var getJumlahTerima = () => {
        let qty = parseInt($('#jumlah_peserta').val());
        let nominal = parseInt($('#nominal').val());
        let upah = parseInt($('#qUpah').val());

        var subtot = (qty * nominal)
        $('#sub_total').val(subtot)
        $('#jumlah_terima').val(subtot - upah)
        $('#div_sub_total').text(commaSeparateNumber(subtot))
        $('#div_jumlah_terima').text(commaSeparateNumber(subtot - upah))
    }
    var getJumlahPeserta = () => {
        $('#jumlah_peserta').val('0');
        let tahun = parseInt($('#qTahun').val())
        let bulan = parseInt($('#qBulan').val())
        let periode = parseInt($('#periode').val())
        $.ajax({
                url: apiUrl + `/jimpitan/getjumlahpeserta/${tahun}/${bulan}/${periode}`,
                type: 'GET',
                dataType: 'json'
            })
            .done(function(data) {
                var jml = data.data;
                console.log(jml)
                $('#jumlah_peserta').val(jml)
                getJumlahTerima()
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
    }

    $(function() {
        $('#qTahun').select2();
        $('#qBulan').select2();
        $('#qUpah').select2();

        bsCustomFileInput.init();
        $('#tanggal_jimpitan').datetimepicker({
            format: 'DD/MM/yyyy HH:mm:ss',
            defaultDate: '{{ date("Y-m-d H:i:s") }}',
        })
        //==== hitung ==
        $('#qTahun').change(() => getJumlahPeserta());
        $('#qBulan').change(() => getJumlahPeserta());
        $('#periode').change(() => getJumlahPeserta());
        $('#jumlah_peserta').change(() => getJumlahTerima());
        $('#qUpah').change(() => getJumlahTerima());

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
