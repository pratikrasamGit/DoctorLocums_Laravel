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
            <h5>Nurse License State</h5>
            {{Form::text('nursing_license_state', $nurse->nursing_license_state,
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('nursing_license_state'), 
            ['class' => 'with-border', 'maxlength' => 15, 'placeholder' => 'ex. Texas']))}}
            @if ($errors->has('nursing_license_state'))
            <small class="invalid-feedback">{{ $errors->first('nursing_license_state') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-6">
        <div class="submit-field">
            <h5>Nurse License Number</h5>
            {{Form::text('nursing_license_number', $nurse->nursing_license_number,
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('nursing_license_number'), 
            ['class' => 'with-border', 'maxlength' => 20]))}}
            @if ($errors->has('nursing_license_number'))
            <small class="invalid-feedback">{{ $errors->first('nursing_license_number') }}</small>
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
    <div class="col-md-6">
        <div class="submit-field">
            <h5>Preferred Geography</h5>
            {{Form::select('work_location', $geographicPreferences,  $availability->work_location, 
                \App\Providers\AppServiceProvider::fieldAttr($errors->has('work_location'), 
                ['class' => 'selectform', 'placeholder' => 'Select Preferred Geography'])
            )}}
            @if ($errors->has('work_location'))
            <small class="invalid-feedback">{{ $errors->first('work_location') }}</small>
            @endif
        </div>
    </div>
</div>