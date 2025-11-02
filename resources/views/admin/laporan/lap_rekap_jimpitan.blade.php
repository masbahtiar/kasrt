@extends(getLayout())
@section('style')
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $judul }}</h3>
        <div class="card-tools">
            <form id="formqr" method="get" action="{{ route('admin.laporan.rekapjimpitan') }}" class="form form-inline">
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
                <a href="{{ route('admin.laporan.rekapjimpitan',['download'=>'pdf','page'=>$page,'tahun'=>$tahun,'bulan'=>$bulan]) }}" id="btnCetak" class="btn btn-md btn-outline-secondary m-2"><i class="fa fa-print"></i></a>
            </form>
        </div>
    </div><!-- /.card-header -->
    <div class="card-body">
        <table class="table table-condensed table-bordered table-hover table-sm text-xs (0.75rem)">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Periode</th>
                    <th>Peserta</th>
                    <th>Nominal</th>
                    <th>Sub Total</th>
                    <th>Upah Pungut</th>
                    <th>Jumlah Terima</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($jimpitans) && $jimpitans->count())
                @foreach ($jimpitans as $key => $item)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->bulan }}</td>
                    <td>{{ $item->periode }}</td>
                    <td>{{ $item->jumlah_peserta }}</td>
                    <td>{{ number_format($item->nominal) }}</td>
                    <td>{{ number_format($item->sub_total) }}</td>
                    <td>{{ number_format($item->upah) }}</td>
                    <td>{{ number_format($item->jumlah_terima) }}</td>
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
                    <th colspan="8">Total</th>
                    <th class="rata-kanan">{{ number_format($total_terima) }}</th>
                </tr>
            </tfoot>
        </table>
    </div><!-- /.card-body -->
    <div class="card-footer">
        {!! $jimpitans->render() !!}
    </div>
</div>
@include('layouts.parts.dialog-print')
@endsection
@section('script')
<script>
    $(function() {
        $('#qTahun').select2();
        $('#qBulan').select2();
        $('#qTahun').change(() => $('#btnCetak').hide())
        $('#qBulan').change(() => $('#btnCetak').hide())
    });
</script>

@endsection
