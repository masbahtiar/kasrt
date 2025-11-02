@extends('layouts.myapp')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/typeahead/typeahead.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
<style>
    #image-preview {
        display: none;
        width: 200px;
        height: auto;
        -webkit-box-shadow: 0 10px 6px -6px #777;
        -moz-box-shadow: 0 10px 6px -6px #777;
        box-shadow: 0 10px 6px -6px #777;
    }

    #image-view {
        width: 300px;
        height: auto;
        -webkit-box-shadow: 0 10px 6px -6px #777;
        -moz-box-shadow: 0 10px 6px -6px #777;
        box-shadow: 0 10px 6px -6px #777;
    }
</style>

@endsection
@section('content')
<div class="col-md-10 col-sm-12 d-flex justify-content-center">
    <div class="card card-info">
        <div class="card-header with-border">
            <h3 class="card-title">{{ $judul }}</h3>
        </div>
        <form method="POST" class="form-horizontal" action="{{ route('admin.updflashmessage') }}" id="updForm" enctype="multipart/form-data">
            <div class="card-body">
                @csrf
                <input type="hidden" name="id" value="{{ $artikel->id }}">
                <input type="hidden" name="user_id" value="{{$artikel->user_id}}">
                <input type="hidden" name="start_date" value="{{ $artikel->start_date }}">
                <input type="hidden" name="end_date" value="{{ $artikel->end_date }}">
                <div class="form-group row">
                    <label for="name" class="col-md-3 control-label">User</label>
                    <div class="col-md-9 {{ $errors->has('judul') ? ' has-error' : '' }}">
                        <div class="form-control-static">{{ $artikel->user()->first()->name }}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-3 control-label">Judul</label>
                    <div class="col-md-9 {{ $errors->has('judul') ? ' has-error' : '' }}">
                        <input id="judul" type="text" class="form-control" name="judul" value="{{ $artikel->judul }}" required autofocus>

                        @if ($errors->has('judul'))
                        <span class="help-block">
                            <strong>{{ $errors->first('judul') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-3 control-label">Isi Pesan</label>
                    <div class="col-md-9 {{ $errors->has('isi_pesan') ? ' has-error' : '' }}">
                        <textarea class="form-control" id="isi_pesan" name="isi_pesan">{{$artikel->isi_pesan}}</textarea>

                        @if ($errors->has('isi_pesan'))
                        <span class="help-block">
                            <strong>{{ $errors->first('isi_pesan') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group row">
                        <label for="name" class="col-md-3 control-label">Tanggal Mulai dan Selesai</label>
                        <div class="col-md-9 {{ $errors->has('start_date') ? ' has-error' : '' }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right" id="reservationtime" name="start_end_date">
                            </div>
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
                <div class="form-group row">
                    <label for="roles" class="col-md-3 control-label">Penerima Pesan</label>
                    <div class="col-md-9">
                        <select class="form-control select2" name="level[]" multiple>
                            @foreach ($roles as $i => $role)
                            <option value="{{$role->role_name}}" {{ $artikel->hasRole($role->role_name)?'selected':'' }}>{{$role->role_name }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-md-3 control-label">Aktif</label>
                    <div class="col-md-4 {{ $errors->has('aktif') ? ' has-error' : '' }}">
                        <select name="aktif" class="custom-select">
                            <option value="yes" {{ $artikel->aktif=='yes'?"selected":"" }}>Yes</option>
                            <option value="no" {{ $artikel->aktif=='no'?"selected":"" }}>No</option>
                        </select>
                        @if ($errors->has('aktif'))
                        <span class="help-block">
                            <strong>{{ $errors->first('aktif') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info pull-right">Simpan</button>
                    <a href="{{ url('admin/flashmessage')}}" class="btn btn-link pull-left">Kembali</a>
                </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset('plugins/typeahead/bloodhound.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/typeahead/typeahead.jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/handlebars-v4.0.11.js')}}"></script>
<script type="text/javascript" src="{{asset('js/app/addartikel.js')}}"></script>
<script src="{{asset('templateEditor/ckeditor/ckeditor.js')}}"></script>
<script>
    // var options = {
    //     filebrowserUploadUrl: "{{url('upload_image')}}",
    // };
    CKEDITOR.replace('isi_pesan', {
        filebrowserUploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2()
        //moment('2017-02-27T00:00:00.000Z').utc().format('YYYY-MM-DD')
        var start = moment('{{$artikel->start_date}}').format('DD-MM-YYYY hh:mm');
        var end = moment('{{$artikel->end_date}}').format('DD-MM-YYYY hh:mm');
        $('#reservationtime').daterangepicker({
            startDate: start,
            endDate: end,
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'DD-MM-YYYY hh:mm A'
            }
        })
    });
</script>


@endsection
