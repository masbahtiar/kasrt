<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-7">
                <h3 class="m-0">{{ isset($judul)? $judul:'' }}</h3>
            </div><!-- /.col -->
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    @if(isset($breadcrumbs))
                    @foreach (Breadcrumbs::generate($breadcrumbs) as $breadcrumb)
                    @if ($breadcrumb->url && !$loop->last)
                    <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                    @else
                    <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
                    @endif
                    @endforeach
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
