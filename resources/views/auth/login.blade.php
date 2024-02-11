@extends('layouts.welcome')
@section('title', 'Nurseify - Login')
@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center min-h-100vh align-items-center">
            <div class="col-md-8 col-lg-6 form-midbox hero-static">
                <div class="" style="background:#ffffff; border:2px solid #269EB2; padding:30px;border-radius: 30px;">
                    <div class="logo">
                        <a href="https://www.nurseify.app/" target="_blank">
                            <img class="d-block mx-auto" width="205" height="" src="data:image/jpeg;base64,{{ base64_encode($logolr) }}" alt="">
                        </a>
                    </div>
                    <h1 class="title text-center my_30">We're glad to see you again!</h1>
                    <div class="lightbg">
                        @include('inc.messages')
                        <div class="row d-flex justify-content-center">

                            <!-- Form Contaner Start -->
                            <div class="col-md-12 form-con">
                                <!-- Form -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input id="email" type="email" maxlength="255"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus style="background: transparent;;border: 2px solid #269EB2;box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.06);border-radius: 31px;">
                                        @error('email')
                                            <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" maxlength="15"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password" style="background: transparent;;border: 2px solid #269EB2;box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.06);border-radius: 31px;">
                                        @error('password')
                                            <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @if (Route::has('password.request'))
                                        <div class="form-group"  style="margin-bottom:20px;">
                                            <a class="forgot-password" href="{{ route('password.request') }}" style="color:#1B2527;">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        </div>
                                    @endif
                                    <input type="hidden" name="recaptcha" id="recaptcha">
                                    <button type="submit" class="button full-width" style="background: #269EB2;border: 2px solid #269EB2; box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.06);border-radius: 31px;">Log In</button>

                                    <button type="button" class="button full-width" style="background: #93e5f3;color:#1B2527;box-shadow: 0px 6px 24px rgba(0, 0, 0, 0.12);border-radius: 200px;margin-top:20px;">
                                        <span style="margin-right:10px;"><img class="" width="20" src="<?php echo url('images/doximity_icon.png'); ?>" alt="" style="border-radius: 50px;"></span> Signin With Doximity
                                    </button>

                                </form>
                                <div class="bottom-text" style="margin-top:20px;">
                                    <p>Don't have an account? @if (Route::has('register'))
                                            <a href="{{ route('register') }}">Sign Up</a>
                                        @endif
                                    </p>
                                </div>
                                <!-- Social Login -->
                                <!-- <div class="social-login-separator"><span>or</span></div>
                                            <div class="social-login-buttons">
                                                <button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Log In via Facebook</button>
                                                <button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log In via Google+</button>
                                            </div> -->

                            </div>
                            <!-- Form Contaner End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('captcha')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ config('services.recaptcha.sitekey') }}", {
                action: 'login'
            }).then(function(token) {
                if (token) {
                    document.getElementById('recaptcha').value = token;
                }
            });
        });
    </script>
@endsection
