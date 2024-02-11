<div class="row">
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Facility</h5>
        {{Form::select('facility_id', $facilities,  $department->facility_id, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('facility_id'), 
        ['class' => 'selectform with-border', 'placeholder' => 'Select Facility']))}}
        @if ($errors->has('facility_id'))
        <small class="invalid-feedback">{{ $errors->first('facility_id') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Department Name</h5>
        {{Form::text('department_name', $department->department_name, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('department_name'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('department_name'))
        <small class="invalid-feedback">{{ $errors->first('department_name') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
    <h5>Department Specialties</h5>
        {{Form::select('department_specialties', $specialities,  $department->department_specialties, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('department_specialties'), 
        ['class' => 'selectform with-border', 'placeholder' => 'Select Specialties']))}}
        @if ($errors->has('department_specialties'))
        <small class="invalid-feedback">{{ $errors->first('department_specialties') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Department Phone</h5>
        {{Form::number('department_phone', $department->department_phone, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('department_phone'), 
        ['class' => 'with-border', 'maxlength' => 15]))}}
        @if ($errors->has('department_phone'))
        <small class="invalid-feedback">{{ $errors->first('department_phone') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Department Numbers</h5>
        {{Form::number('department_numbers', $department->department_numbers, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('department_numbers'), 
        ['class' => 'with-border', 'maxlength' => 10]))}}
        @if ($errors->has('department_numbers'))
        <small class="invalid-feedback">{{ $errors->first('department_numbers') }}</small>
        @endif
    </div>
</div>
{{ Form::hidden('url', URL::previous()) }}
</div>