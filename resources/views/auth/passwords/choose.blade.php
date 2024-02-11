@extends('layouts.welcome')
@section('title', 'Nurseify - Choose Password')
@section('content')
<div class="container">
  <div class="row d-flex justify-content-center">
    <div class="col-md-8 col-lg-6 form-midbox hero-static" style="{{$back_sm}}">
      <div class="logo">
        <img src="data:image/jpeg;base64,{{ base64_encode($logolr) }}" alt="">
      </div>
      <h1 class="title">Choose Password</h1>
      <div class="lightbg">
        <div class="row d-flex justify-content-center">
          <!-- Form Contaner Start -->
          <div class="col-md-12 form-con">
            <!-- Form -->
            <form action="{{$url}}" method="POST">
              @csrf
              <input type="hidden" name="token" value="{{ $token }}">
              <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" placeholder="E-Mail" class="form-control" name="email" value="{{$email}}" required disabled="disabled">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" maxlength="15" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                <small class="invalid-feedback">{{ $message }}</small>
                @enderror
                <small class="notice">Note : Your password must be more than 6 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</small>
              </div>
              <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" maxlength="15" class="form-control" name="password_confirmation" required autocomplete="new-password">
              </div>
              <div class="form-group">
                <button type="submit" class="button full-width">
                  {{ __('Choose Password') }}
                </button>
              </div>
            </form>
            <div class="bottom-text">
              <p>Back to Login? @if(Route::has('login'))<a href="{{ route('login') }}">Log In!</a>@endif</p>
            </div>
          </div>
          <!-- Form Contaner End -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection