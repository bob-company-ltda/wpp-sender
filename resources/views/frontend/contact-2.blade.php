@extends('frontend.layouts.main2')
@section('content')
<style>
    .row{
        display:block !important;
    }
    .nav-link{
        padding:0 !important;
    }
</style>
   <section class="waf1">
       
       <div class="contact_page_section">
      <div class="container">
          <div class="contact_inner">
              <div class="contact_form">
                <div class="section_title">
                    <h2>Leave a <span>message</span></h2>
                    <p>Fill up form below, our team will get back soon</p>
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
                    <div class="form-group">
                    <input type="text" required="" name="name" maxlength="20" placeholder="{{ __('Enter your Name') }}" class="form-control @error('name') is-invalid @enderror">
                    </div>
                    <div class="form-group">
                    <input type="email" required="" name="email" maxlength="40" placeholder="{{ __('Enter your Mail') }}" class="form-control @error('email') is-invalid @enderror">
                    </div>
                    <div class="form-group">
                    <input type="number" required="" name="phone" maxlength="15" placeholder="{{ __('Enter your Number') }}" class="form-control @error('phone') is-invalid @enderror">
                    </div>
                    <div class="form-group">
                    <input type="text" placeholder="{{ __('Subject') }}" maxlength="100" required="" name="subject" class="form-control @error('subject') is-invalid @enderror">
                    </div>
                    <div class="form-group">
                    <textarea placeholder="{{ __('Type your Message') }}" maxlength="500" required="" name="message" class="form-control @error('message') is-invalid @enderror"></textarea>
                    </div>
                    <div class="form-group term_check">
                      <input type="checkbox" id="term">
                      <label for="term">I agree to receive emails, newsletters and promotional messages</label>
                    </div>
                    <div class="form-group mb-0">
                    <button type="submit" class="btn puprple_btn">SEND MESSAGE</button>
                    </div>
                  </form>
              </div>

              <div class="contact_info">
                 
                  <ul class="contact_info_list">
                    <li>
                      <div class="img">
                        <img src="{{ asset('assets/frontend/images/mail_icon.png')}}" alt="image">
                      </div>
                      <div class="text">
                        <span>Email Us</span>
                        <a href="mailto:{{ $contact_page->email1 ?? '' }}">{{ $contact_page->email1 ?? '' }}</a>
                      </div>
                    </li>
                    <li>
                      <div class="img">
                        <img src="{{ asset('assets/frontend/images/call_icon.png')}}" alt="image">
                      </div>
                      <div class="text">
                        <span>Call Us</span>
                        <a href="tel:{{ $contact_page->contact1 ?? '' }}">tel:{{ $contact_page->contact1 ?? '' }}</a>
                      </div>
                    </li>
                    <li>
                      <div class="img">
                        <img src="{{ asset('assets/frontend/images/location_icon.png')}}" alt="image">
                      </div>
                      <div class="text">
                        <span>Visit Us</span>
                        <p>{{ $contact_page->address }}</p>
                      </div>
                    </li>
                  </ul>
              </div>
          </div>
      </div>
      </div>
    </section>
@endsection
