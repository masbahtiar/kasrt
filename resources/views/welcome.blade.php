<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'DAPATFORSA') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('front/img/core-img/favicon.ico') }}">

    <!-- start baru -->
    <link rel="stylesheet" href="{{ asset('front/style.css') }}">
    <style>
        .breakpoint-off .classynav ul li .dropdown {
            width: 300px;
            position: absolute;
            background-color: #ffffff;
            top: 120%;
            left: 0;
            z-index: 100;
            height: auto;
            box-shadow: 0 1px 5px rgb(0 0 0 / 10%);
            -webkit-transition-duration: 300ms;
            transition-duration: 300ms;
            opacity: 0;
            visibility: visible;
            padding: 10px 0;
            overflow-x: scroll;
        }
    </style>
    <!-- end baru -->

</head>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>

    <!-- ##### Header Area Start ##### -->
    <header class="header-area">

        <!-- Navbar Area -->
        <div class="mag-main-menu" id="sticker">
            <div class="classy-nav-container breakpoint-off">
                <!-- Menu -->
                @include('layouts.parts.front.header-menu')
            </div>
        </div>
    </header>
    <!-- ##### Header Area End ##### -->

    <!-- ##### Hero Area Start ##### -->
    <div class="hero-area owl-carousel">
        <!-- Single Blog Post -->
        @foreach ($main_slide as $slide )
        <div class="hero-blog-post bg-img bg-overlay" style="background-image: url({{ asset('upload/artikel/'.$slide->artikel->image_url)}})">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12">
                        <!-- Post Contetnt -->
                        <div class="post-content text-center">
                            <div class="post-meta" data-animation="fadeInUp" data-delay="100ms">
                                <a href="{{url('/artikel')}}/{{$slide->artikel->id}}">{{ $slide->artikel->updated_at }}</a>
                                <a href="{{url('/artikel/kategori/'.$slide->artikel->kategori_id)}}">{{ $slide->artikel->kategori->nm_kategori }}</a>
                            </div>
                            <a href="{{url('/artikel')}}/{{$slide->artikel->id}}" class="post-title" data-animation="fadeInUp" data-delay="300ms">{{ strtoupper($slide->artikel->judul) }}</a>
                            <p style="color: #ffff;" data-animation="fadeInUp" data-delay="350ms">
                                {!! \Illuminate\Support\Str::limit(strip_tags($slide->artikel->isi_artikel), $limit = 400, $end = '...') !!}
                            </p>
                            <!-- <a href="{{ asset('front/video-post.html') }}" class="video-play" data-animation="bounceIn" data-delay="500ms"><i class="fa fa-play"></i></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endforeach

    </div>

    <!-- ##### Hero Area End ##### -->

    <!-- ##### Mag Posts Area Start ##### -->
    <section class="mag-posts-area d-flex flex-wrap">

        <!-- >>>>>>>>>>>>>>>>>>>>
         Post Left Sidebar Area
        <<<<<<<<<<<<<<<<<<<<< -->
        <div class="post-sidebar-area left-sidebar mt-30 mb-30 bg-white box-shadow">
            <!-- Sidebar Widget -->
            <div class="single-sidebar-widget p-30">
                <!-- Section Title -->
                <div class="section-heading">
                    <h5>Artikel Terbaru</h5>
                </div>
                @foreach ( $latest as $lts)
                <!-- Single Blog Post -->
                <div class="single-blog-post d-flex">
                    <div class="post-thumbnail">
                        <img src="{{ asset('upload/artikel/'.$lts->thumb_image_url) }}" alt="">
                    </div>
                    <div class=" post-content">
                        <a href="{{url('/artikel')}}/{{$lts->id}}" class="post-title">{{ $lts->judul }}</a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        <!-- >>>>>>>>>>>>>>>>>>>>
             Main Posts Area
        <<<<<<<<<<<<<<<<<<<<< -->
        <div class="mag-posts-content mt-30 mb-30 p-30 box-shadow">
            <!-- Trending Now Posts Area -->
            <div class="trending-now-posts mb-30">
                <!-- Section Title -->
                <div class="section-heading">
                    <h5>TRENDING NOW</h5>
                </div>

                <div class="trending-post-slides owl-carousel">
                    @foreach ( $top_news as $top)
                    <!-- Single Trending Post -->
                    <div class="single-trending-post">
                        <img style="width: 300px; height:200px;" src="{{ asset('upload/artikel/'.$top->artikel->image_url) }}" alt="">
                        <div class="post-content">
                            <a class="post-cata" href="{{url('/artikel')}}/{{$top->artikel->id}}">{{ $top->artikel->kategori->nm_kategori }}</a>
                            <a class="post-title" href="{{url('/artikel/kategori/'.$top->artikel->kategori_id)}}">{{ $top->artikel->judul }}</a>
                        </div>
                    </div>
                    @endforeach


                </div>
            </div>

            <!-- Feature Video Posts Area -->

            <!-- Most Viewed Videos -->
            <div class="most-viewed-videos mb-30">
                <!-- Section Title -->
                <div class="section-heading">
                    <h5>Pengumuman Terbaru</h5>
                </div>

                <div class="most-viewed-videos-slide owl-carousel">
                    @foreach ( $pengumuman as $top)
                    <!-- Single Blog Post -->
                    <div class="single-blog-post style-4">
                        <div class="post-thumbnail">
                            <img style="width: 250px; height:150px" src="{{ asset('upload/artikel/'.$top->thumb_image_url) }}" alt="">
                            <a href="{{url('/artikel/kategori/'.$top->kategori_id)}}" ) }}" class="video-play"><i class="fa fa-search"></i></a>
                        </div>
                        <div class="post-content">
                            <a href="{{url('/artikel')}}/{{$top->id}}" class="post-title">{{ $top->judul }}</a>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <!-- Sports Videos -->
            <div class="sports-videos-area">
                <!-- Section Title -->
                <div class="section-heading">
                    <h5>Artikel Terbaru</h5>
                </div>

                <div class="sports-videos-slides owl-carousel mb-30">
                    @foreach ( $latest as $lts)
                    <!-- Single Featured Post -->
                    <div class="single-featured-post">
                        <!-- Thumbnail -->
                        <div class="post-thumbnail mb-50">
                            <img style="height: 350px;width:100%" src="{{ asset('upload/artikel/'.$lts->thumb_image_url) }}" alt="">
                            <a href="{{url('/artikel')}}/{{$lts->id}}" ) }}" class="video-play"><i class="fa fa-search"></i></a>
                        </div>
                        <!-- Post Contetnt -->
                        <div class="post-content">
                            <div class="post-meta">
                                <a href="#">MAY 8, 2018</a>
                                <a href="{{ asset('front/archive.html') }}">lifestyle</a>
                            </div>
                            <a href="{{url('/artikel')}}/{{$lts->id}}" ) }}" class="post-title">{{ $lts->judul }}</a>
                            <p>
                                {!! \Illuminate\Support\Str::limit(strip_tags($lts->isi_artikel), $limit = 400, $end = '...') !!}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- >>>>>>>>>>>>>>>>>>>>
         Post Right Sidebar Area
        <<<<<<<<<<<<<<<<<<<<< -->
        <div class="post-sidebar-area right-sidebar mt-30 mb-30 box-shadow">
            <!-- Sidebar Widget -->
            <!-- <div class="single-sidebar-widget p-30"> -->
            <!-- Social Followers Info -->
            <!-- <div class="social-followers-info">
                    Facebook
                    <a href="#" class="facebook-fans"><i class="fa fa-facebook"></i> 4,360 <span>Fans</span></a>
                    Twitter
                    <a href="#" class="twitter-followers"><i class="fa fa-twitter"></i> 3,280 <span>Followers</span></a>
                    YouTube
                    <a href="#" class="youtube-subscribers"><i class="fa fa-youtube"></i> 1250 <span>Subscribers</span></a>
                    Google
                    <a href="#" class="google-followers"><i class="fa fa-google-plus"></i> 4,230 <span>Followers</span></a> -->
            <!-- </div> -->
            <!-- </div> -->

            <!-- Sidebar Widget -->
            <div class="single-sidebar-widget p-30">
                <!-- Section Title -->
                <div class="section-heading">
                    <h5>KATEGORI</h5>
                </div>

                <!-- Catagory Widget -->
                <ul class="catagory-widgets">
                    @foreach($kategoris as $kategori)
                    <li>
                        <a href="{{url('/artikel/kategori/'.$kategori->id)}}"><span><i class="fa fa-angle-double-right" aria-hidden="true"></i> {{$kategori->nm_kategori}}</span></a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Sidebar Widget -->
            <div class="single-sidebar-widget p-30">
                <!-- Section Title -->
                <div class="section-heading">
                    <h5>Profil</h5>
                </div>

                @foreach ( $profil as $lts)
                <!-- Single YouTube Channel -->
                <div class="single-youtube-channel d-flex">
                    <div class="youtube-channel-thumbnail">
                        <img src="{{ asset('upload/artikel/'.$lts->thumb_image_url) }}" alt="">
                    </div>
                    <div class="youtube-channel-content">
                        <a href="{{url('/artikel')}}/{{$lts->id}}" class="channel-title">{{ $lts->judul }}</a>
                    </div>
                </div>
                @endforeach

            </div>

            <!-- Sidebar Widget -->
            <div class="single-sidebar-widget p-30">
                <!-- Section Title -->
                <div class="section-heading">
                    <h5>Pengumuman</h5>
                </div>

                <div class="newsletter-form">
                    @foreach ( $pengumuman as $lts)
                    <!-- Single YouTube Channel -->
                    <ul>
                        <li style="border-bottom: #e23636 solid 1px;"><a href="{{url('/artikel')}}/{{$lts->id}}" class="channel-title">{{ strtoupper($lts->judul) }}</a>
                        </li>
                    </ul>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
    <!-- ##### Mag Posts Area End ##### -->

    <!-- ##### Footer Area Start ##### -->
    @include('layouts.parts.front.footer')

    <!-- ##### Footer Area End ##### -->

    <!-- ##### All Javascript Script ##### -->
    <!-- jQuery-2.2.4 js -->
    <script src="{{ asset('front/js/jquery/jquery-2.2.4.min.js') }}"></script>
    <!-- Popper js -->
    <script src="{{ asset('front/js/bootstrap/popper.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('front/js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- All Plugins js -->
    <script src="{{ asset('front/js/plugins/plugins.js') }}"></script>
    <!-- Active js -->
    <script src="{{ asset('front/js/active.js') }}"></script>
</body>

</html>