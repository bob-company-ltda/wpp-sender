@extends('frontend.layouts.main')
@section('content')

<div class="full_bg">
<div class="container">
    
    <section class="signup_section">

          <div class="top_part">

          <a href="{{ url('/') }}" class="back_btn"><i class="icofont-arrow-left"></i> Back</a>
            <a class="navbar-brand" href="{{ url('/') }}">
              <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" alt="image">
            </a>
          </div>
          
          <div class="signup_form">
            <div class="section_title">
            <h2>{{ __('Reset Password') }}</h2>
            <p>{{ __('Rest Your Account your Account') }}</p>
<form method="POST" action="{{ route('password.update') }}">
    <input type="hidden" name="token" value="{{ $token }}">
<div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
              </div>
              
              <div class="form-group">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="new-password">
              @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
            </div>
            
            
            <div class="form-group">
              <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Confirm Password" name="password_confirmation" required autocomplete="new-password">
              @error('password')
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