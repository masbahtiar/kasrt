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
            font-size: 11pt;
        }

        table th {
            font-size: 11pt;
        }

        .page-break {
            page-break-after: always;
        }

        .rata-kanan {
            text-align: right;
            padding: 2;
        }

        .rata-tengah {
            text-align: center;
            padding: 2;
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
                <th>Akun</th>
                <th>Debet</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($neracas) && $neracas->count())
            @foreach ($neracas as $key => $item)
            <tr>
                <td class="rata-tengah" width="30">{{ ++$key }}</td>
                <td width="300">{{ $item->nmsubakun }}</td>
                <td class="rata-kanan">{{ number_format($item->debet) }}</td>
                <td class="rata-kanan">{{ number_format($item->kredit) }}</td>
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
