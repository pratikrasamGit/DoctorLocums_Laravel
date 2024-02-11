@extends('layouts.welcome')
@section('title', 'Nurseify - Account Sign Up')
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

                                    <button type="button" class="button full-width" style="background: #93e5f3;color:#1B2527;box-shadow: 0px 6px 24px rgba(0, 0, 0, 0.12);border-radius: 200px;margin-top:20px;">
                                        <span style="margin-right:10px;"><img class="" width="20" src="<?php echo url('images/doximity_icon.png'); ?>" alt="" style="border-radius: 50px;"></span> Verify With Doximity
                                    </button>

                                </form>
                                <div class="bottom-text text-center" style="margin-top:20px;">
                                    <p>Already have an account?  @if (Route::has('login'))
                                            <a href="{{ route('login') }}">Log In</a>
                                        @endif
                                    </p>
                                </div>
                                <div class="bottom-text text-center">
                                    <p>Interested in our nurse internship program? <a href="https://www.nurseify.app/nurse-internship-program">Click Here</a>
                                    </p>
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
@section('captcha')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ config('services.recaptcha.sitekey') }}", {
                action: 'register'
            }).then(function(token) {
                if (token) {
                    document.getElementById('recaptcha').value = token;
                }
            });
        });
    </script>
@endsection
