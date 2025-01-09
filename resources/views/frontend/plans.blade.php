@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.header')
  <main>
      @include('frontend.pricings')
      @include('frontend.sections.faq')
   </main>
@endsection