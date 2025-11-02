@extends(getLayout())
@section('style')
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $judul }}</h3>
        <div class="card-tools">
            <form id="formqr" method="get" action="{{ route('admin.laporan.neraca') }}" class="form form-inline">
                <div class="form-group m-2">
                    <label for="name" class="control-label mr-2">Tahun</label>
                    <select class="form-control" id="qTahun" name="tahun">
                        @foreach($tahun_opt as $key =>$value)
                        <option value="{{ $value }}" {{$tahun == $value?"selected":""}}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-2">
                    <label for="name" class="control-label mr-2">Bulan</label>
                    <select class="form-control" id="qBulan" name="bulan">
                        @foreach($bulan_opt as $key =>$value)
                        <option value="{{ $key }}" {{$bulan == $key?"selected":""}}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-md btn-secondary" type="submit"><i class="fa fa-search"></i></button>
                <a href="{{ route('admin.laporan.neraca',['download'=>'pdf','page'=>$page,'tahun'=>$tahun,'bulan'=>$bulan]) }}" id="btnCetak" class="btn btn-md btn-outline-secondary m-2"><i class="fa fa-print"></i></a>
            </form>
        </div>
    </div><!-- /.card-header -->
    <div class="card-body">
        <table class="table table-condensed table-bordered table-hover table-sm text-xs (0.75rem)">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Akun</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($neracas) && $neracas->count())
                @foreach ($neracas as $key => $item)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $item->nmsubakun }}</td>
                    <td>{{ number_format($item->debet) }}</td>
                    <td>{{ number_format($item->kredit) }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="10">Data Kosong</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th class="rata-kanan">{{ number_format($tot_debet) }}</th>
                    <th class="rata-kanan">{{ number_format($tot_kredit) }}</th>
                </tr>
            </tfoot>
        </table>
    </div><!-- /.card-body -->
    <div class="card-footer">
        {!! $neracas->render() !!}
    </div>
</div>
@include('layouts.parts.dialog-print')
@endsection
@section('script')
<script>
    $(function() {
        $('#qTahun').select2();
        $('#qBulan').select2();
    });
</script>

@endsection
