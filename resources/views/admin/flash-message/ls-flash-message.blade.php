@extends('layouts.myapp')
@section('style')
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{ $judul  }}</h3>
                <div class="card-tools">
                    <a href="{{ url('admin/flashmessage/add') }}" class="btn btn-sm btn-info">Tambah</a>
                </div>
            </div>
            <div class="card-body">
                <table id="tbllsuser" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" width="100%">
                    <thead>
                        <tr class="bg-gray" style="font-size:10pt; font: bold;">
                            <th width="20">
                                <center>ID</center>
                            </th>
                            <th width="200">
                                <center>JUDUL</center>
                            </th>
                            <th width="100">
                                <center>START DATE</center>
                            </th>
                            <th width="100">
                                <center>END DATE</center>
                            </th>
                            <th width="200">
                                <center>USER</center>
                            </th>
                            <th width="200">
                                <center>AKTIF</center>
                            </th>
                            <th>
                                <center>PERINTAH</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="20">
                                <center>ID</center>
                            </th>
                            <th width="200">
                                <center>JUDUL</center>
                            </th>
                            <th width="100">
                                <center>START DATE</center>
                            </th>
                            <th width="100">
                                <center>END DATE</center>
                            </th>
                            <th width="200">
                                <center>USER</center>
                            </th>
                            <th width="200">
                                <center>AKTIF</center>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<!-- <script src="{{asset('js/moment-with-locales.min.js')}}"></script> -->

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
                "url": "{{ route('admin.lsflashmessage') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        _token: "{{csrf_token()}}",
                    })
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
                    "data": "judul"
                },
                {
                    "data": "start_date"
                },
                {
                    "data": "end_date"
                },
                {
                    "data": "name"
                },
                {
                    "data": "aktif"
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
        $('#kategori_id').change(function(event) {
            dTable.draw();
        });
        $('#btnTambah').click(function(event) {
            event.preventDefault();
            var url = $('#kategori_id').val();
            window.location = "{{ url('admin/flashmessage/add')}}/" + url;
            /* Act on the event */
        });
    });
</script>
@endsection
