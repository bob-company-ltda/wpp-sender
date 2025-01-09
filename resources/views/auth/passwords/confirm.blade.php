@extends('layouts.app')

@extends('frontend.layouts.main')
@section('content')
<div class="full_bg">
<div class="container">
    
    <section class="signup_section">
        
         <div class="top_part">
          @if (Route::has('password.request'))
                                    <a class="back_btn" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
            <a class="navbar-brand" href="{{ url('/') }}">
              <img src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" alt="image">
            </a>
          </div>
        
         
        
         <div class="signup_form">
            <div class="section_title">
            <h2>{{ __('Confirm Password') }}</h2>
            <p>{{ __('Please confirm your password before continuing.') }}</p>
            <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        
                        <div class="form-group">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
              @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
            </div>
            
             <div class="form-group">
                <button class="btn puprple_btn" type="submit">{{ __('Confirm Password') }}</button>
              </div>

                    </form>
                 </div>
         </section>
         </div>
      
    </div>
@endsection