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
            <h2>{{ __('Welcome') }}<span> {{ __('back') }} </span></h2>
            <p>{{ __('Login your Account') }}</p>
            @if(session('auth-error'))
    <span style="display:block !important;" class="invalid-feedback" role="alert">
        <strong>{{ session('auth-error') }}</strong>
    </span>
@endif

            <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                @error('email')
                                    <span style="display:block !important class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
              </div>
              <div class="form-group">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
              @error('password')
                                    <span style="display:block !important class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
            </div>
            @if (Route::has('password.request'))
                                    <a class="" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
            <div class="form-group">
                <button class="btn puprple_btn" type="submit">SIGN IN</button>
              </div>
              </form>
            <p class="or_block">
              <span>OR</span>
            </p>
            <div class="or_option">
            <p>Don't have an account? <a href="{{ url('/pricing') }}">Sign Up here</a></p>
           </div>
          </div>
         </section>
         </div>
      
    </div>
@endsection
