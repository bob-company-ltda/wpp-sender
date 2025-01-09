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
            <h2><span> {{ __('Create,') }} </span>{{ __('an Account') }}</h2>
            <p>{{ __('Register with '.$plan->title) }}</p>
            <form method="POST" action="{{ url('register-plan',$plan->id) }}">
            @csrf
            <div class="form-group">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
            @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
         </div>
              <div class="form-group">
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ $request->email ??  old('email') }}" required autocomplete="email" autofocus>
              @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
            </div>
              <div class="form-group">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
              @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  
            </div>
            <div class="form-group">
                <button class="btn puprple_btn" type="submit">{{ __('SIGN UP') }}</button>
              </div>
            </form>
            <p class="or_block">
              <span>OR</span>
            </p>
           <div class="or_option">
           <p>Already have an account? <a href="{{ url('/login') }}">{{ __('SIGN IN') }}</a></p>
           </div>
          </div>
         </section>
      </div>   
</div>
@endsection
