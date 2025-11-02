@extends(getLayout())
@section('style')
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $judul }}</h3>
        <div class="card-tools">
            <form id="formqr" method="get" action="{{ route('admin.laporan.bukubesar', $kdsubakun) }}" class="form form-inline">
                <input type="hidden" name="saldo_awal" value="{{ $saldo_awal }}">
                <div class="input-group m-2" id="start_date" data-target-input="nearest">
                    <input name="start_date" type="text" class="form-control datetimepicker-input" data-target="#start_date" />
                    <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <div class="input-group m-2" id="end_date" data-target-input="nearest">
                    <input name="end_date" type="text" class="form-control datetimepicker-input" data-target="#end_date" />
                    <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <div class="m-2">
                    <select id="kdsubakun" name="kdsubakun" class="form-control">
                        @foreach($kdsubakuns as $key =>$value)
                        <option value="{{ $value->kdsubakun }}" {{$kdsubakun == $value->kdsubakun?"selected":""}}>{{ $value->nmsubakun }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-md btn-secondary" type="submit"><i class="fa fa-search"></i></button>
                <a href="{{ route('admin.laporan.bukubesar',[$kdsubakun,'download'=>'pdf','page'=>$page,'start_date'=>$start_date->format('d/m/Y'),'end_date'=>$end_date->format('d/m/Y')]) }}" id="btnCetak" class="btn btn-md btn-outline-secondary m-2"><i class="fa fa-print"></i></a>
            </form>
        </div>
    </div><!-- /.card-header -->
    <div class="card-body">
        <table class="table table-condensed table-bordered table-hover table-sm text-xs (0.75rem)">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Ref</th>
                    <th width="80">Tanggal</th>
                    <th>Akun</th>
                    <th>Keterangan</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($bukubesars) && $bukubesars->count())
                @foreach ($bukubesars as $key => $item)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $item->no_ref }}</td>
                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$item->tanggal_jurnal)->format('d-m-Y') }}</td>
                    <td>{{ $item->nmsubakun }}</td>
                    <td>{{ $item->ket_jurnal }}</td>
                    <td>{{ number_format($item->debet) }}</td>
                    <td>{{ number_format($item->kredit) }}</td>
                    <td>{{ number_format($item->saldo) }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="10">Data Kosong</td>
                </tr>
                @endif
            </tbody>
            </tbody>
        </table>
    </div><!-- /.card-body -->
    <div class="card-footer">
        {!! $bukubesars->render() !!}
    </div>
</div>
@include('layouts.parts.dialog-print')
@endsection

@section('script')
<script>
    $(function() {
        $('#kdsubakun').select2();
        $('#start_date').datetimepicker({
            format: 'DD/MM/yyyy',
            locale: moment.locale(),
            defaultDate: moment('{{ $start_date }}'),
        })
        $('#end_date').datetimepicker({
            format: 'DD/MM/yyyy',
            locale: moment.locale(),
            defaultDate: moment('{{ $end_date }}'),
        })
        var act = $('#formqr').attr('action');
        var xact = act.split('bukubesar/');
        $('#kdsubakun').change(() => {
            $('#formqr').attr('action', xact[0] + 'bukubesar/' + $('#kdsubakun').val())
            $('#btnCetak').css({
                'visibility': 'hidden'
            });
        })
    });
</script>

@endsection
