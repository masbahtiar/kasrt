@extends('layouts.myapp')
@section('style')
<link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
@endsection
@section('content')
<div class="card">
    <div class="card-header">

        <h3 class="card-title">{{ $judul }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.jimpitan.create') }}" class="btn btn-info btn-sm">
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
                        <center>Tanggal</center>
                    </th>
                    <th width="100">
                        <center>No Ref</center>
                    </th>
                    <th width="150">
                        <center>Keterangan</center>
                    </th>
                    <th width="350">
                        <center>Tahun</center>
                    </th>
                    <th width="100">
                        <center>Bulan</center>
                    </th>
                    <th width="100">
                        <center>Periode</center>
                    </th>
                    <th width="100">
                        <center>Jumlah</center>
                    </th>
                    <th width="60">
                        <center>Menu</center>
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
<script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script>
    $(function() {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });

        $('.filter-container').filterizr({
            gutterPixels: 3
        });
        $('.btn[data-filter]').on('click', function() {
            $('.btn[data-filter]').removeClass('active');
            $(this).addClass('active');
        });
    })
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
        }
        return val;
    }

    $(document).ready(function() {
        dTable = $('#tbllsuser').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('admin.jimpitan.list') }}",
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
                    "data": "tanggal_jimpitan",
                    "render": (data, type, row) => {
                        return moment(data).format('DD-MM-YYYY')
                    }
                },
                {
                    "data": "no_ref"
                },
                {
                    "data": "ket_jimpitan"
                },
                {
                    "data": "tahun"
                },
                {
                    "data": "bulan"
                },
                {
                    "data": "periode"
                },
                {
                    "data": "jumlah_terima",
                    "render": (data, type, row) => commaSeparateNumber(data)
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
        $('#tbllsuser tbody').on('click', 'tr td a.del-btn', function(event) {
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
