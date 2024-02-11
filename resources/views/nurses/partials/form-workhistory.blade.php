<div class="row">
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Organization Name</h5>
            {{Form::text('organization_name', $experience->organization_name, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('organization_name'), 
            ['class' => 'with-border', 'maxlength' => 255]))}}
            @if ($errors->has('organization_name'))
            <small class="invalid-feedback">{{ $errors->first('organization_name') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Department(s)</h5>
            {{Form::text('organization_department_name', $experience->organization_department_name, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('organization_department_name'), 
            ['class' => 'with-border', 'maxlength' => 255, 'placeholder' => 'ex. ER Med/Surg, OBGYN']))}}
            @if ($errors->has('organization_department_name'))
            <small class="invalid-feedback">{{ $errors->first('organization_department_name') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Position Title</h5>
            {{Form::text('position_title', $experience->position_title, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('position_title'), 
            ['class' => 'with-border', 'maxlength' => 100, 'placeholder' => 'ex. Medical/Surgical Nurse']))}}
            @if ($errors->has('position_title'))
            <small class="invalid-feedback">{{ $errors->first('position_title') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>City</h5>
            {{Form::text('exp_city', $experience->exp_city, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('exp_city'), 
            ['class' => 'with-border', 'maxlength' => 20]))}}
            @if ($errors->has('exp_city'))
            <small class="invalid-feedback">{{ $errors->first('exp_city') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>State</h5>
            {{Form::select('exp_state', $states, $experience->exp_state, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('exp_state'), 
            ['class' => 'selectform with-border']))}}
            @if ($errors->has('exp_state'))
            <small class="invalid-feedback">{{ $errors->first('exp_state') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Facility Type</h5>
            {{Form::select('facility_type', $facilityTypes, $experience->facility_type, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('facility_type'), 
            ['class' => 'selectform with-border', 'placeholder' => 'Select Facility Type']))}}
            @if ($errors->has('facility_type'))
            <small class="invalid-feedback">{{ $errors->first('facility_type') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Start Date</h5>
            {{Form::date('start_date', $experience->start_date, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('start_date'),
            ['id' => 'start_date','class' => 'with-border']))}}
            @if ($errors->has('start_date'))
            <small class="invalid-feedback">{{ $errors->first('start_date') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>End Date</h5>
            {{Form::date('end_date', $experience->end_date, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('end_date'),
            ['id' => 'end_date','class' => 'with-border']
            ))}}
            @if ($errors->has('end_date'))
            <small class="invalid-feedback">{{ $errors->first('end_date') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-12">
        <div class="submit-field">
            <h5>Description of Job Duties</h5>
            {{Form::textarea('description_job_duties', $experience->description_job_duties, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('description_job_duties'),
            ['class' => 'with-border', 'placeholder' => 'About Job Duties', 'maxlength' => 500,
            'cols' => '20', 'rows' => '3']
            ))}}
            @if ($errors->has('description_job_duties'))
            <small class="invalid-feedback">{{ $errors->first('description_job_duties') }}</small>
            @endif
        </div>
    </div>
</div>