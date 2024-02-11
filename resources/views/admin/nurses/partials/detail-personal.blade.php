@php
$profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/8810d9fb-c8f4-458c-85ef-d3674e2c540a');
if ($nurse->user->image) {
$t = \Illuminate\Support\Facades\Storage::exists('assets/nurses/profile/'.$nurse->user->image);
if ($t) {
$profileNurse = \Illuminate\Support\Facades\Storage::get('assets/nurses/profile/'.$nurse->user->image);
}
}
@endphp
<!-- Dashboard Box -->
<div class="col-xl-12">
    <div class="dashboard-box">
        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-material-outline-account-circle"></i> My Personal Details</h3>
        </div>
        <div class="content with-padding">
            <div class="row">
                <div class="col-auto">
                    <div class="avatar-wrapper" data-tippy-placement="bottom" title="Change Avatar">
                        <img onclick="filechose_button.click()" class="profile-pic img" src="data:image/jpeg;base64,{{ base64_encode($profileNurse) }}" alt="{{$user->getFullNameAttribute()}}">
                        {{Form::file('image',['id' => 'filechose_button','class' => 'file-upload'])}}
                    </div>
                </div>
                <div class="col">
                    @include('users.form')
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="submit-field">
                                <h5>Address</h5>
                                {{Form::text('address', $nurse->address, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('address'), 
                                ['class' => 'with-border', 'maxlength' => 190]))}}
                                @if ($errors->has('address'))
                                <small class="invalid-feedback">{{ $errors->first('address') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>City</h5>
                                {{Form::text('city', $nurse->city, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('city'), 
                                ['class' => 'with-border', 'maxlength' => 20]))}}
                                @if ($errors->has('city'))
                                <small class="invalid-feedback">{{ $errors->first('city') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>State</h5>
                                {{Form::select('state', $states,  $nurse->state, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('state'), 
                                ['class' => 'selectform with-border']))}}
                                @if ($errors->has('state'))
                                <small class="invalid-feedback">{{ $errors->first('state') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>Postcode</h5>
                                {{Form::text('postcode', $nurse->postcode, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('postcode'), 
                                ['class' => 'with-border', 'maxlength' => 6]))}}
                                @if ($errors->has('postcode'))
                                <small class="invalid-feedback">{{ $errors->first('postcode') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>Country</h5>
                                {{Form::text('country', $nurse->country, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('country'), 
                                ['class' => 'with-border', 'maxlength' => 20]))}}
                                @if ($errors->has('country'))
                                <small class="invalid-feedback">{{ $errors->first('country') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="submit-field">
                                <h5>Select Specialities</h5>
                                {{Form::select('specialty[]', $specialities,  $nurse->specialty, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('specialty'), 
                                ['class' => 'selectform','multiple' => 'multiple']))}}
                                @if ($errors->has('specialty'))
                                <small class="invalid-feedback">{{ $errors->first('specialty') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>Nursing License State</h5>
                                {{Form::text('nursing_license_state', $nurse->nursing_license_state, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('nursing_license_state'), 
                                ['class' => 'with-border', 'maxlength' => 50]))}}
                                @if ($errors->has('nursing_license_state'))
                                <small class="invalid-feedback">{{ $errors->first('nursing_license_state') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>Nursing License Number</h5>
                                {{Form::text('nursing_license_number', $nurse->nursing_license_number, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('nursing_license_number'), 
                                ['class' => 'with-border', 'maxlength' => 20]))}}
                                @if ($errors->has('nursing_license_number'))
                                <small class="invalid-feedback">{{ $errors->first('nursing_license_number') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>