<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicon -->
    <link href="{{ asset('images/fav.png')}}" rel=icon>

    <!-- Bootstrap -->
    <link href="{{ asset('app/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- font-awesome -->
    <link href="{{ asset('app/assets/fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Mobile Menu Style -->
    <link href="{{ asset('app/assets/css/mobile-menu.css') }}" rel="stylesheet">

    <!-- Owl carousel -->
    <link href="{{ asset('app/assets/css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('app/assets/css/owl.theme.default.min.css') }}" rel="stylesheet">
    <!-- Theme Style -->
    <link href="{{ asset('app/assets/css/style.css') }}" rel="stylesheet">

    
    
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar">
<div id="main-wrapper">

<!-- Page Preloader -->
<div id="preloader" style="display: none;">
    <div id="status">
        <div class="status-mes"></div>
    </div>
</div>

<div class="uc-mobile-menu-pusher">
<div class="content-wrapper" :key="$route.fullPath">
<section id="header_section_wrapper" class="header_section_wrapper">
    <div class="container">
        <div class="header-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="left_section">
                                            <span class="date">
                                                {{ $day }}
                                            </span>
                        <!-- Date -->
                                            <span class="time">
                                                {{ $date_time }}
                                            </span>
                        <!-- Time -->
                        <div class="social">
                            <a class="icons-sm fb-ic"><i class="fa fa-facebook"></i></a>
                            <!--Twitter-->
                            <a class="icons-sm tw-ic"><i class="fa fa-twitter"></i></a>
                            <!--Google +-->
                            <a class="icons-sm inst-ic"><i class="fa fa-instagram"> </i></a>
                            <!--Linkedin-->
                            <a class="icons-sm tmb-ic"><i class="fa fa-tumblr"> </i></a>
                            <!--Pinterest-->
                            <a class="icons-sm rss-ic"><i class="fa fa-rss"> </i></a>
                        </div>
                        <!-- Top Social Section -->
                    </div>
                    <!-- Left Header Section -->
                </div>
                <div class="col-md-4">
                    <div class="logo">
                        <a href="/"><img src="/app/assets/img/logo.png" alt="Heal NewsLogo"></a>
                    </div>
                    <!-- Logo Section -->
                </div>
                {{-- <div class="col-md-4">
                    <div class="right_section">
                        <ul class="nav navbar-nav">
                            <li><a href="#">Login</a></li>
                            <li><a href="#">Register</a></li>
                            <li class="dropdown lang">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">En <i
                                        class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="#">Bn</a></li>
                                </ul>
                            </li>
                        </ul>
                        <!-- Language Section -->

                        <ul class="nav-cta hidden-xs">
                            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i
                                    class="fa fa-search"></i></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="head-search">
                                            <form role="form">
                                                <!-- Input Group -->
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                           placeholder="Type Something"> <span class="input-group-btn">
                                                                            <button type="submit"
                                                                                    class="btn btn-primary">Search
                                                                            </button>
                                                                        </span></div>
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!-- Search Section -->
                    </div>
                    <!-- Right Header Section -->
                </div> --}}
            </div>
        </div>
        <!-- Header Section -->
        
        <navigation></navigation>
        <!-- .navigation-section -->

    </div>
    <!-- .container -->
</section>
<!-- header_section_wrapper -->

<section class="breadcrumb_section">
    <div class="container">
        <div class="row">
            {{-- <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">News</a></li>
                <li><a href="#">Tech</a></li>
                <li class="active"><a href="#">Mobile</a></li>
            </ol> --}}
        </div>
    </div>
</section>


<div class="container">
    @yield('content')
</div>
<!-- container -->

<section id="footer_section" class="footer_section">
    <div class="footer_bottom_Section">
        <div class="container">
            <div class="row">
                <div class="footer">
                    <div class="col-sm-3">
                        <div class="social">
                            <a class="icons-sm fb-ic"><i class="fa fa-facebook"></i></a>
                            <!--Twitter-->
                            <a class="icons-sm tw-ic"><i class="fa fa-twitter"></i></a>
                            <!--Google +-->
                            <a class="icons-sm inst-ic"><i class="fa fa-instagram"> </i></a>
                            <!--Linkedin-->
                            <a class="icons-sm tmb-ic"><i class="fa fa-tumblr"> </i></a>
                            <!--Pinterest-->
                            <a class="icons-sm rss-ic"><i class="fa fa-rss"> </i></a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <p>&copy; Copyright 2018-Health News . Thiết kế bởi: <a href="#">Health News</a>
                        </p></div>
                    <div class="col-sm-3">
                        <p>Tin Tức Sức Khoẻ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<!-- #content-wrapper -->

</div>
<!-- .offcanvas-pusher -->

<a href="#" class="crunchify-top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>

<div class="uc-mobile-menu uc-mobile-menu-effect">
    <button type="button" class="close" aria-hidden="true" data-toggle="offcanvas"
            id="uc-mobile-menu-close-btn">&times;</button>
    <div>
        <div>
            <ul id="menu">
                <li class="active"><a href="blog.html">News</a></li>
                <li><a href="category.html">Mobile</a></li>
                <li><a href="blog.html">Tablet</a></li>
                <li><a href="category.html">Gadgets</a></li>
                <li><a href="blog.html">Camera</a></li>
                <li><a href="category.html">Design</a></li>
                <li class="dropdown m-menu-fw"><a href="#" data-toggle="dropdown" class="dropdown-toggle">More
                    <span><i class="fa fa-angle-down"></i></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="m-menu-content">
                                <ul class="col-sm-3">
                                    <li class="dropdown-header">Widget Haeder</li>
                                    <li><a href="#">Awesome Features</a></li>
                                    <li><a href="#">Clean Interface</a></li>
                                    <li><a href="#">Available Possibilities</a></li>
                                    <li><a href="#">Responsive Design</a></li>
                                    <li><a href="#">Pixel Perfect Graphics</a></li>
                                </ul>
                                <ul class="col-sm-3">
                                    <li class="dropdown-header">Widget Haeder</li>
                                    <li><a href="#">Awesome Features</a></li>
                                    <li><a href="#">Clean Interface</a></li>
                                    <li><a href="#">Available Possibilities</a></li>
                                    <li><a href="#">Responsive Design</a></li>
                                    <li><a href="#">Pixel Perfect Graphics</a></li>
                                </ul>
                                <ul class="col-sm-3">
                                    <li class="dropdown-header">Widget Haeder</li>
                                    <li><a href="#">Awesome Features</a></li>
                                    <li><a href="#">Clean Interface</a></li>
                                    <li><a href="#">Available Possibilities</a></li>
                                    <li><a href="#">Responsive Design</a></li>
                                    <li><a href="#">Pixel Perfect Graphics</a></li>
                                </ul>
                                <ul class="col-sm-3">
                                    <li class="dropdown-header">Widget Haeder</li>
                                    <li><a href="#">Awesome Features</a></li>
                                    <li><a href="#">Clean Interface</a></li>
                                    <li><a href="#">Available Possibilities</a></li>
                                    <li><a href="#">Responsive Design</a></li>
                                    <li><a href="#">Pixel Perfect Graphics</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- .uc-mobile-menu -->

</div>
<!-- #main-wrapper -->

<script src="{{ asset('js/app.js') }}"></script>

<!-- jquery Core-->
<script src="{{ asset('app/assets/js/jquery-2.1.4.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('app/assets/js/bootstrap.min.js') }}"></script>

<!-- Theme Menu -->
<script src="{{ asset('app/assets/js/mobile-menu.js') }}"></script>

<!-- Owl carousel -->
<script src="{{ asset('app/assets/js/owl.carousel.min.js') }}"></script>

<!-- Theme Script -->
<script src="{{ asset('app/assets/js/script.js') }}"></script>


</body>

</html>