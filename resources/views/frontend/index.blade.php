@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.header')
<div class="body-overlay"></div>
<main>
      @include('frontend.sections.hero')
      @include('frontend.sections.trusted')
      @include('frontend.sections.features',['limit'=> 6])
       @include('frontend.sections.about')
       @include('frontend.sections.features-2')
      @include('frontend.sections.work')
      @include('frontend.pricings')
      @include('frontend.sections.feedback-1')
      @include('frontend.sections.faq')
      @include('frontend.sections.area')
   </main>
@endsection
