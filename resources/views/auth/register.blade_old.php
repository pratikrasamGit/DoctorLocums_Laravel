@extends('layouts.welcome')
@section('title', 'Nurseify - Account Sign Up')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 form-midbox hero-static" style="{{ $back_sm }}">
            <div class="logo">
                <a href="https://www.nurseify.app/" target="_blank">
                    <img src="data:image/jpeg;base64,{{ base64_encode($logolr) }}" alt="" style="width: 40%;">
                </a>
            </div>
            <h1 class="title">Account Sign up</h1>
            <div class="lightbg">
                @include('inc.messages')
                <div class="row d-flex justify-content-center">

                    <!-- Form Contaner Start -->
                    <div class="col-md-10 col-lg-8 col-xl-7 form-con">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input id="first_name" type="text" maxlength="100" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
                                        @error('first_name')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input id="last_name" type="text" maxlength="100" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
                                        @error('last_name')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input id="mobile" type="number" maxlength="15" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus>
                                        @error('mobile')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" maxlength="255" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="password" maxlength="15" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        @error('password')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password-confirm">Confirm Password</label>
                                        <input id="password-confirm" maxlength="15" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <small class="notice">Note : Your password must be more than 6
                                            characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric
                                            and 1 special character.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nursing_license_state">Nurse License State</label>
                                        <input id="nursing_license_state" maxlength="15" type="text" placeholder="ex. Texas" class="form-control @error('nursing_license_state') is-invalid @enderror" name="nursing_license_state" value="{{ old('nursing_license_state') }}" required autocomplete="nursing_license_state" autofocus>
                                        @error('nursing_license_state')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nursing_license_number">Nurse License Number</label>
                                        <input id="nursing_license_number" maxlength="20" type="text" class="form-control @error('nursing_license_number') is-invalid @enderror" name="nursing_license_number" value="{{ old('nursing_license_number') }}" required autocomplete="nursing_license_number" autofocus>
                                        @error('nursing_license_number')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('specialty', 'Specialty') }}
                                        {{ Form::select('specialty',$specialties,'',\App\Providers\AppServiceProvider::fieldAttr($errors->has('specialty'), ['class' => 'selectform','placeholder' => 'Select Specialty'])) }}
                                        @error('specialty')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('work_location', 'Preferred Geography') }}
                                        {{ Form::select('work_location',$workLocations,'',\App\Providers\AppServiceProvider::fieldAttr($errors->has('work_location'), ['class' => 'selectform','placeholder' => 'Select Preferred Geography'])) }}
                                        @error('work_location')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="recaptcha" id="recaptcha">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-5">
                                    <button type="submit" class="button full-width">Submit</button>
                                </div>
                            </div>
                        </form>
                        <div class="bottom-text">
                            <p>Already have an account? @if (Route::has('login'))
                                <a href="{{ route('login') }}">Log In!</a>
                                @endif
                            </p>
                            <p>Interested in our nurse internship program? <a target="_blank" href="https://www.nurseify.app/nurse-internship-program">Click Here</a></p>
                        </div>
                    </div>
                    <!-- Form Contaner End -->
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
