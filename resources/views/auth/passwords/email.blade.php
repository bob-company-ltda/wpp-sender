@extends('frontend.layouts.main')
@section('content')

<div class="full_bg">
<div class="container">
         <section class="signup_section">

          <div class="top_part">

          <a href="{{ url('/login') }}" class="back_btn"><i class="icofont-arrow-left"></i> Back</a>
            <a class="navbar-brand" href="{{ url('/') }}">
              <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" alt="image">
            </a>
          </div>
          
          <div class="signup_form">
            <div class="section_title">
            <h2>{{ __('Reset Password') }}</span></h2>
            <p>{{ __('Reset your Account Password') }}</p>
<form method="POST" action="{{ route('password.email') }}">
                             @csrf
                             
                             <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
              </div>
          
          
          <div class="form-group">
                <button class="btn puprple_btn" type="submit">{{ __('Reset Password') }}</button>
              </div>
 
                           </form>
                        </div>
         </section>
         </div>
      
    </div>
@endsection