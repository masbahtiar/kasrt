<!DOCTYPE html>
<html>

<head>
    <title>{{ $judul }}</title>
    <style type="text/css">
        table td,
        table th {
            border: 1px solid black;
        }

        .header {
            width: 100%;
            position: relative;
        }

        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }

        .header {
            top: 0px;
        }

        .footer {
            bottom: 0px;
        }

        .pagenum:before {
            content: counter(page, decimal);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        td,
        th {
            border: 1px solid black;
            padding: 2px;
        }

        table td {
            font-size: 9pt;
        }

        table th {
            font-size: 9pt;
        }

        .page-break {
            page-break-after: always;
        }

        .rata-kanan {
            text-align: right;
        }

        .rata-tengah {
            text-align: center;
        }

        table.ttd {
            width: 100%;
            border: none;
            text-align: center;
        }

        table.ttd td {
            border: none;
            padding: 2px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>
    <div class="header">
        <h2>{{ $judul }}</h2>
        <h4>{{ $bulan>0?'Bulan : '.$bulan_opt[$bulan].', ':'' }} Tahun : {{ $tahun }}</h4>
    </div>
    <div class="footer">
        Page <span class="pagenum"></span>
    </div>

    <table class="table table-bordered ">
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Periode</th>
                <th>Peserta</th>
                <th>Nominal</th>
                <th>Sub Total</th>
                <th>Upah Petugas</th>
                <th>Jumlah Terima</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($jimpitans) && $jimpitans->count())
            @foreach ($jimpitans as $key => $item)
            <tr>
                <td class="rata-tengah">{{ ++$key }}</td>
                <td class="rata-tengah">{{ $item->tahun }}</td>
                <td class="rata-tengah">{{ $item->bulan }}</td>
                <td class="rata-tengah">{{ $item->periode }}</td>
                <td class="rata-tengah">{{ $item->jumlah_peserta }}</td>
                <td class="rata-kanan">{{ number_format($item->nominal) }}</td>
                <td class="rata-kanan">{{ number_format($item->sub_total) }}</td>
                <td class="rata-kanan">{{ number_format($item->upah) }}</td>
                <td class="rata-kanan">{{ number_format($item->jumlah_terima) }}</td>
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
    <div class="page-break"></div>
    <table class="ttd">
        <tr>
            <td style="width: 40%;"></td>
            <td colspan="2">Pati, {{ date('d M Y') }}</td>
        </tr>
        <tr>
            <td style="width: 40%;"></td>
            <td colspan="2">Pengurus RT</td>
        </tr>
        <tr>
            <td style="width: 40%;"></td>
            <td>Ketua</td>
            <td>Bendahara</td>
        </tr>
        <tr>
            <td colspan="3" style="height: 30px;"></td>
        </tr>
        <tr>
            <td style="width: 40%;"></td>
            <td>{{ $ketua_rt}}</td>
            <td>{{ $bendahara_rt }}</td>
        </tr>
    </table>

</body>

</html>
