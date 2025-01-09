@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.header')
   <main>
   <div class="bred_crumb">
      <div class="container">
         @foreach (['banner_shape1', 'banner_shape2', 'banner_shape3'] as $shape)
            <span class="{{ $shape }}"> 
               <img src="{{ asset("assets/frontend/images/{$shape}.png") }}" alt="image">
            </span>
         @endforeach
         <div class="bred_text">
            <h1>{{ __('Contact us') }}</h1>
            <p>{{ __('If you have a query, please get in touch with us. We will revert back quickly.') }}</p>
         </div>
      </div>
   </div>
   <section class="contact_page_section">
      <div class="container">
         <div class="contact_inner">
            <div class="contact_form">
               <div class="section_title">
                  <h2>{{ __('Leave a') }} <span>{{ __('message') }}</span></h2>
                  <p>{{ __('Fill up the form below, our team will get back soon.') }}</p>
               </div>
               @if ($errors->any())
                  <div class="alert alert-danger">
                     <ul>
                        @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                        @endforeach
                     </ul>
                  </div>
               @endif
               @if(Session::has('success'))
                  <div class="alert alert-success" role="alert">
                     {{ Session::get('success') }}
                  </div>
               @endif
               @if(Session::has('error'))
                  <div class="alert alert-danger" role="alert">
                     {{ Session::get('error') }}
                  </div>
               @endif
               <form action="{{ route('send.mail') }}" method="POST">
                  @csrf
                  @foreach ([
                     ['name', __('Enter your Name'), 'text', 20],
                     ['email', __('Enter your Mail'), 'email', 40],
                     ['phone', __('Enter your Number'), 'number', 15],
                     ['subject', __('Subject'), 'text', 100]
                  ] as [$field, $placeholder, $type, $max])
                     <div class="form-group">
                        <input type="{{ $type }}" name="{{ $field }}" required="" maxlength="{{ $max }}" placeholder="{{ $placeholder }}" class="form-control @error($field) is-invalid @enderror">
                     </div>
                  @endforeach
                  <div class="form-group">
                     <textarea placeholder="{{ __('Type your Message') }}" required="" name="message" maxlength="500" class="form-control @error('message') is-invalid @enderror"></textarea>
                  </div>
                  <div class="form-group term_check">
                     <input type="checkbox" id="term">
                     <label for="term">{{ __('I agree to receive emails, newsletters and promotional messages') }}</label>
                  </div>
                  <div class="form-group mb-0">
                     <button type="submit" class="btn puprple_btn">{{ __('SEND MESSAGE') }}</button>
                  </div>
               </form>
            </div>
            <div class="contact_info">
               <div class="icon"><img src="{{ asset('assets/frontend/images/contact_message_icon.png') }}" alt="image"></div>
               <div class="section_title">
                  <h2>{{ __('Have any') }} <span>{{ __('question?') }}</span></h2>
                  <p>{{ __('If you have any question about our product, service, payment or company.') }}</p>
               </div>
               <ul class="contact_info_list">
                  @foreach ([
                     ['email1', 'Email Us', 'mail_icon.png', 'mailto:'],
                     ['contact1', 'Call Us', 'call_icon.png', 'tel:'],
                     ['address', 'Visit Us', 'location_icon.png', null]
                  ] as [$key, $label, $icon, $prefix])
                     @if ($contact_page->$key ?? false)
                        <li>
                           <div class="img">
                              <img src="{{ asset("assets/frontend/images/{$icon}") }}" alt="{{ strtolower($label) }}">
                           </div>
                           <div class="text">
                              <span>{{ $label }}</span>
                              @if ($prefix)
                                 <a href="{{ $prefix . $contact_page->$key }}">{{ $contact_page->$key }}</a>
                              @else
                                 <p>{{ $contact_page->$key }}</p>
                              @endif
                           </div>
                        </li>
                     @endif
                  @endforeach
               </ul>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection
