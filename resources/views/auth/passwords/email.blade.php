@extends('layouts.welcome')
@section('title', 'Nurseify - Forget Password')
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
                    <h1  class="title text-center my_30">Reset Password</h1>
                    <div class="lightbg">
                        <div class="row d-flex justify-content-center">
                            <!-- Form Contaner Start -->
                            <div class="col-md-12 form-con">
                                <!-- Form -->
                                @if(session('status'))
                                <div class="notification success closeable">
                                    <p>{{session('status')}}</p>
                                    <a class="close" href="#"></a>
                                </div>
                                @endif
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input id="email" type="email" maxlength="255" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus  style="background: transparent;;border: 2px solid #269EB2;box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.06);border-radius: 31px;">
                                        @error('email')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="button full-width" style="background: #269EB2;border: 2px solid #269EB2; box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.06);border-radius: 31px;">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </form>
                                <div class="bottom-text" style="margin-top:20px;">
                                    <p>Back to Login? @if(Route::has('login'))<a href="{{ route('login') }}">Log In</a>@endif</p>
                                </div>
                            </div>
                            <!-- Form Contaner End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


</div>
@endsection
