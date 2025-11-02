@extends('layouts.myapp')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengguna Aplikasi</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.adduser') }}" class="btn btn-tool"><i class="fas fa-plus-square"></i> <span>Tambah</span></a>
                </div>
            </div>
            <div class="card-body">
                <table id="tbllsuser" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                        <tr class="bg-gray" style="font-size:10pt; font: bold;">
                            <th width="20">
                                <center>Id</center>
                            </th>
                            <th width="200">
                                <center>Nama Lengkap</center>
                            </th>
                            <th width="150">
                                <center>Username</center>
                            </th>
                            <th width="100">
                                <center>Email</center>
                            </th>
                            <th width="30">
                                <center>Role</center>
                            </th>
                            <th>
                                <center>Perintah</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="20">
                                <center>Id</center>
                            </th>
                            <th width="200">
                                <center>Nama Lengkap</center>
                            </th>
                            <th width="150">
                                <center>Nama User</center>
                            </th>
                            <th width="100">
                                <center>Email</center>
                            </th>
                            <th width="30">
                                <center>Role</center>
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
                "url": "{{ route('admin.lsuser') }}",
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
                    "data": "name"
                },
                {
                    "data": "username"
                },
                {
                    "data": "email"
                },
                {
                    "data": "role",
                    "sortable": false
                },
                {
                    "data": "options",
                    "sortable": false
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
        $('#tbllsuser tbody').on('click', 'tr td a:nth-child(3)', function(event) {
            event.preventDefault();
            remArtikel($(this).attr('href'))
        });

        var remArtikel = function(url) {
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
