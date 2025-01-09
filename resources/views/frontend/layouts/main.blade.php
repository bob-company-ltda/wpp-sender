<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
   {!! SEOMeta::generate() !!}
   {!! OpenGraph::generate() !!}
   {!! Twitter::generate() !!}
   {!! JsonLd::generate() !!}
   
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}">

   <!-- CSS here -->
   <link rel="stylesheet" href="{{ asset('assets/frontend/css/icofont.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/frontend/css/owl.carousel.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">
   <!--link rel="stylesheet" href="{{ asset('assets/frontend/css/aos.css') }}"-->
   <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/frontend/css/responsive.css') }}">
</head>

<body>
<!-- Page-wrapper-Start -->
  <div class="page_wrapper">

    <!-- Preloader -->
    <div id="preloader">
      <div id="loader"></div>
    </div>
    @yield('content')
    @if (!request()->is('login') && !request()->is('register*'))
    @include('frontend.layouts.footer')
@endif


   <!-- JS here -->
   <script src="{{ asset('assets/frontend/js/jquery.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/owl.carousel.min.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
   <!--script src="{{ asset('assets/frontend/js/aos.js') }}"></script-->
   <script src="{{ asset('assets/frontend/js/jquery.magnific-popup.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/main.js') }}"></script>
</body>
</html>