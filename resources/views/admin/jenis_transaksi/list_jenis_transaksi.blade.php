@extends('layouts.myapp')

@section('content')
<div class="card">
    <div class="card-header">

        <h3 class="card-title">{{ $judul }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.jenistransaksi.create') }}" class="btn btn-info btn-sm">
                <span class="fas fa-plus-sign"></span>&nbsp;&nbsp;Tambah
            </a>
        </div>
    </div><!-- /.card-header -->


    <div class="card-body">

        <table id="tbllsuser" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" width="100%">
            <thead>
                <tr class="bg-gray" style="font-size:10pt; font: bold;">
                    <th width="20">
                        <center>Id</center>
                    </th>
                    <th width="100">
                        <center>Nama Jenis Transaksi</center>
                    </th>
                    <th width="300">
                        <center>Akun Debet</center>
                    </th>
                    <th width="150">
                        <center>Akun Kredit</center>
                    </th>
                    <th width="300">
                        <center>Keterangan</center>
                    </th>
                    <th>
                        <center>Perintah</center>
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        dTable = $('#tbllsuser').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('admin.jenistransaksi.list') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    _token: "{{csrf_token()}}"
                }
            },
            "bJQueryUI": false,
            "responsive": true,
            "iDisplayLength": 10,
            "aLengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "aaSorting": [
                [0, "asc"]
            ],
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "nama_jtransaksi"
                },
                {
                    "data": "akun_debet"
                },
                {
                    "data": "akun_kredit"
                },
                {
                    "data": "keterangan"
                },
                {
                    "data": "options",
                    "sortable": false,
                    "class": "menu"
                }
            ],
            "oLanguage": {
                "sEmptyTable": "No incompleted albums found!", //when empty
                "sSearch": "<span>Pencarian:</span> _INPUT_", //search
                "sLengthMenu": "<span>Tampilkan data:</span> _MENU_", //label
                "sZeroRecords": "<center>Tidak ada data</center>",
                "sEmptyTable": "<center>Tidak ada data</center>",
                "sInfo": "Halaman _PAGE_ dari _PAGES_ dari _TOTAL_ data",
                "sInfoEmpty": "Tidak ada data",
                "sProcessing": "<img src={{asset('img/loader.gif')}}>", //<img src='path/to/ajax-loader.gif'>
                "sLoadingRecords": "Loading...",
                "sInfoFiltered": "(Pencarian Dari _MAX_ total data)",
                "oPaginate": {
                    "sFirst": "First",
                    "sLast": "Last",
                    "sNext": ">>",
                    "sPrevious": "<<"
                } //pagination
            },

        });
        $('#tbllsuser tbody').on('click', 'tr td.menu a:nth-child(2)', function(event) {
            event.preventDefault();
            remDesa($(this).attr('href'))
        });
        var remDesa = function(url) {
            bootbox.confirm("apakah ingin menghapus data ?", function(result) {
                if (result == true) {
                    $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json'
                        })
                        .done(function(data) {
                            console.log(data.message);
                            dTable.draw();
                        })
                        .fail(function() {
                            console.log("error");
                        })
                        .always(function() {
                            console.log("complete");
                        });

                }
            })
        }
    });
</script>
@endsection
