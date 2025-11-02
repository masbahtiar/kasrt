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
            content: counter(page);
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
        <table class="table">
            <tr>
                <td>Akun</td>
                <td>:</td>
                <td>{{ strtoupper($nmsubakun) }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$start_date)->format('d/m/Y')." -> ". Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$end_date)->format('d/m/Y')}}</td>
            </tr>
        </table>
    </div>
    <div class="footer">
        Page <span class="pagenum"></span>
    </div>

    <table class="table table-bordered ">
        <tr>
            <th>No</th>
            <th>No Ref</th>
            <th width="80">Tanggal</th>
            <th width="200">Keterangan</th>
            <th>Debet</th>
            <th>Kredit</th>
            <th>Saldo</th>
        </tr>
        @foreach ($bukubesars as $key => $item)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $item->no_ref }}</td>
            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$item->tanggal_jurnal)->format('d-m-Y') }}</td>
            <td>{{ $item->ket_jurnal }}</td>
            <td>{{ number_format($item->debet) }}</td>
            <td>{{ number_format($item->kredit) }}</td>
            <td>{{ number_format($item->saldo) }}</td>
        </tr>
        @endforeach
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
