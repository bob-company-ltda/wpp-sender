@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.header')
<section class="row_am faq_section">
      <div class="container">
        <div class="section_title">
          <h2>{{ __('Frequently asked questions') }} 📣</h2>
        </div>
        <div class="faq_panel">
          <div class="accordion" id="accordionExample">
          @foreach($faqs as $key => $faq)
          @if($faq->slug != 'top')
            <div class="card">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button type="button" class="btn btn-link active" data-toggle="collapse" data-target="#collapse{{ $key+1 }}">
                  	<i class="icon_faq icofont-plus"></i></i> {{ $faq->title }}</button>
                </h2>
              </div>
              <div id="collapse{{ $key+1 }}" class="collapse show" aria-labelledby="heading{{ $key+1 }}" data-parent="#accordionExample">
                <div class="card-body">
                  <p>{{ $faq->excerpt->value ?? '' }}</p>
                </div>
              </div>
            </div>
            @endif
            @endforeach
          </div>
        </div>
      </div>
    </section>
@endsection