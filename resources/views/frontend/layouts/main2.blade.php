<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <meta content="width=device-width, initial-scale=1.0, maximum-scale=5.0" name="viewport">
   <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
    <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
   {!! SEOMeta::generate() !!}
   {!! OpenGraph::generate() !!}
   {!! Twitter::generate() !!}
   {!! JsonLd::generate() !!}
   
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- Place favicon.ico in the root directory -->
   <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('primary_data',true)->favicon ?? '') }}">

   <!-- CSS here -->
   <link rel="stylesheet" href="{{ asset('assets/frontend/css/style2.css') }}">
</head>


<body class="home page-template page-template-wacloud-home-page page-template-wacloud-home-page-php page page-id-6 wp-embed-responsive group-blog has-featured-image featured-image-wide" >
    <div id ="myDiv" class="laoctionsetting wacloudAS wacloudIN" >
        
        @include('frontend.layouts.header-2')
        @yield('content')
    
    </div>

   <!-- JS here -->
   <script src="{{ asset('assets/frontend/js/jquery.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/lazy_load.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
   <script src="{{ asset('assets/frontend/js/style2.js') }}"></script>
</body>
</html>