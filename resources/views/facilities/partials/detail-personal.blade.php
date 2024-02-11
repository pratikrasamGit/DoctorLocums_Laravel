<div class="row">
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Facility Logo</h5> 
        <div class="uploadButton">
            {{Form::file('facility_logo',['id' => 'facility_logo','class' => 'with-border'])}}            
        </div>
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
	@if(Illuminate\Support\Facades\Storage::exists('assets/facilities/facility_logo/'.$facility->facility_logo)
	&& $facility->facility_logo)
		<div>
			<img class="max-width-20" src="data:image/jpeg;base64,{{ base64_encode(Illuminate\Support\Facades\Storage::get('assets/facilities/facility_logo/'.$facility->facility_logo)) }}" alt="Facility Logo">
		</div>
	@endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Facility Name</h5>
        {{Form::text('name', $facility->name,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('name'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('name'))
            <small class="invalid-feedback">{{ $errors->first('name') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Facility Type</h5>
        {{Form::select('type', $facilityTypes,  $facility->type, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('type'), 
        ['class' => 'selectform with-border', 'placeholder' => 'Select Facility Type']))}}
        @if ($errors->has('type'))
        <small class="invalid-feedback">{{ $errors->first('type') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Facility Email</h5>
        {{Form::text('facility_email', $facility->facility_email, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('facility_email'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('facility_email'))
        <small class="invalid-feedback">{{ $errors->first('facility_email') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Facility Phone</h5>
        {{Form::number('facility_phone', $facility->facility_phone, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('facility_phone'), 
        ['class' => 'with-border', 'maxlength' => 15]))}}
        @if ($errors->has('facility_phone'))
        <small class="invalid-feedback">{{ $errors->first('facility_phone') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Address</h5>
        {{Form::text('address', $facility->address, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('address'), 
        ['class' => 'with-border', 'maxlength' => 190]))}}
        @if ($errors->has('address'))
        <small class="invalid-feedback">{{ $errors->first('address') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-4">
    <div class="submit-field">
        <h5>City</h5>
        {{Form::text('city', $facility->city, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('city'), 
        ['class' => 'with-border', 'maxlength' => 20]))}}
        @if ($errors->has('city'))
        <small class="invalid-feedback">{{ $errors->first('city') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-4">
    <div class="submit-field">
        <h5>State</h5>
        {{Form::select('state', $states,  $facility->state, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('state'), 
        ['class' => 'selectform with-border']))}}
        @if ($errors->has('state'))
        <small class="invalid-feedback">{{ $errors->first('state') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-4">
    <div class="submit-field">
        <h5>Postcode</h5>
        {{Form::text('postcode', $facility->postcode, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('postcode'), 
        ['class' => 'with-border', 'maxlength' => 6]))}}
        @if ($errors->has('postcode'))
        <small class="invalid-feedback">{{ $errors->first('postcode') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Senior Leader Message</h5>
        {{Form::textarea('cno_message', $facility->cno_message, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('cno_message'), 
        ['id' => 'editor-1', 'class' => 'with-border', 'maxlength' => 500, 'cols' => '15', 'rows' => '5']))}}
        @if ($errors->has('cno_message'))
        <small class="invalid-feedback">{{ $errors->first('cno_message') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Headshot CNO</h5>            
        <div class="uploadButton">
            {{Form::file('cno_image',['id' => 'cno_image','class' => 'with-border'])}}
        </div>
        @if(Illuminate\Support\Facades\Storage::exists('assets/facilities/cno_image/'.$facility->cno_image)
	    && $facility->cno_image)
		<div>
			<img class="max-width-20" src="data:image/jpeg;base64,{{ base64_encode(Illuminate\Support\Facades\Storage::get('assets/facilities/cno_image/'.$facility->cno_image)) }}" alt="Facility CNO HeadShot">
		</div>
	    @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>About Facility</h5>
        {{Form::textarea('about_facility', $facility->about_facility, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('about_facility'), 
        ['id' => 'editor', 'class' => 'with-border', 'cols' => '15', 'rows' => '5']))}}
        @if ($errors->has('about_facility'))
        <small class="invalid-feedback">{{ $errors->first('about_facility') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Website</h5>
        {{Form::text('facility_website', $facility->facility_website,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('facility_website'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('facility_website'))
            <small class="invalid-feedback">{{ $errors->first('facility_website') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>YouTube / Vimeo Link</h5>
        {{Form::text('video', $facility->video,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('video'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('video'))
            <small class="invalid-feedback">{{ $errors->first('video') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Electronic Medical Records (EMR)</h5>
        {{Form::select('f_emr', $eMedicalRecords,  $facility->f_emr, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('f_emr'), 
        ['id' => 'f_emr', 'class' => 'selectform with-border', 'placeholder' => 'Select Electronic Medical Records (EMR)']))}}
        @if ($errors->has('f_emr'))
        <small class="invalid-feedback">{{ $errors->first('f_emr') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6 hide emr">
    <div class="submit-field">
        <h5>Other Electronic Medical Records (EMR)</h5>
        {{Form::text('f_emr_other', $facility->f_emr_other, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('f_emr_other'), 
        ['class' => 'with-border', 'maxlength' => 150]))}}
        @if ($errors->has('f_emr_other'))
        <small class="invalid-feedback">{{ $errors->first('f_emr_other') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Background Check Provider</h5>
        {{Form::select('f_bcheck_provider', $bCheckProviders,  $facility->f_bcheck_provider, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('f_bcheck_provider'), 
        ['id' => 'f_bcheck_provider', 'class' => 'selectform with-border', 'placeholder' => 'Select Background Check Provider']))}}
        @if ($errors->has('f_bcheck_provider'))
        <small class="invalid-feedback">{{ $errors->first('f_bcheck_provider') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6 hide bcp">
    <div class="submit-field">
        <h5>Other Background Check Provider</h5>
        {{Form::text('f_bcheck_provider_other', $facility->f_bcheck_provider_other, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('f_bcheck_provider_other'), 
        ['class' => 'with-border', 'maxlength' => 150]))}}
        @if ($errors->has('f_bcheck_provider_other'))
        <small class="invalid-feedback">{{ $errors->first('f_bcheck_provider_other') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Nurse Credentialing Software</h5>
        {{Form::select('nurse_cred_soft', $nCredentialingSoftwares,  $facility->nurse_cred_soft, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('nurse_cred_soft'), 
        ['id' => 'nurse_cred_soft', 'class' => 'selectform with-border', 'placeholder' => 'Select Nurse Credentialing Software']))}}
        @if ($errors->has('nurse_cred_soft'))
        <small class="invalid-feedback">{{ $errors->first('nurse_cred_soft') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6 hide ncs">
    <div class="submit-field">
        <h5>Other Nurse Credentialing Software</h5>
        {{Form::text('nurse_cred_soft_other', $facility->nurse_cred_soft_other, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('nurse_cred_soft_other'), 
        ['class' => 'with-border', 'maxlength' => 150]))}}
        @if ($errors->has('nurse_cred_soft_other'))
        <small class="invalid-feedback">{{ $errors->first('nurse_cred_soft_other') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Nurse Scheduling System</h5>
        {{Form::select('nurse_scheduling_sys', $nSchedulingSystems,  $facility->nurse_scheduling_sys, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('nurse_scheduling_sys'), 
        ['id' => 'nurse_scheduling_sys', 'class' => 'selectform with-border', 'placeholder' => 'Select Nurse Scheduling System']))}}
        @if ($errors->has('nurse_scheduling_sys'))
        <small class="invalid-feedback">{{ $errors->first('nurse_scheduling_sys') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6 hide nss">
    <div class="submit-field">
        <h5>Other Nurse Scheduling System</h5>
        {{Form::text('nurse_scheduling_sys_other', $facility->nurse_scheduling_sys_other, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('nurse_scheduling_sys_other'), 
        ['class' => 'with-border', 'maxlength' => 150]))}}
        @if ($errors->has('nurse_scheduling_sys_other'))
        <small class="invalid-feedback">{{ $errors->first('nurse_scheduling_sys_other') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Time & Attendance System</h5>
        {{Form::select('time_attend_sys', $timeAttendanceSystems,  $facility->time_attend_sys, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('time_attend_sys'), 
        ['id' => 'time_attend_sys', 'class' => 'selectform with-border', 'placeholder' => 'Select Time & Attendance System']))}}
        @if ($errors->has('time_attend_sys'))
        <small class="invalid-feedback">{{ $errors->first('time_attend_sys') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6 hide tas">
    <div class="submit-field">
        <h5>Other Time & Attendance System</h5>
        {{Form::text('time_attend_sys_other', $facility->time_attend_sys_other, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('time_attend_sys_other'), 
        ['class' => 'with-border', 'maxlength' => 150]))}}
        @if ($errors->has('time_attend_sys_other'))
        <small class="invalid-feedback">{{ $errors->first('time_attend_sys_other') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Licensed Beds</h5>
        {{Form::text('licensed_beds', $facility->licensed_beds, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('licensed_beds'), 
        ['class' => 'with-border', 'maxlength' => 20]))}}
        @if ($errors->has('licensed_beds'))
        <small class="invalid-feedback">{{ $errors->first('licensed_beds') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Trauma Designation</h5>
        {{Form::select('trauma_designation', $traumaDesignations,  $facility->trauma_designation, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('trauma_designation'), 
        ['class' => 'selectform with-border', 'placeholder' => 'Select Trauma Designation']))}}
        @if ($errors->has('trauma_designation'))
        <small class="invalid-feedback">{{ $errors->first('trauma_designation') }}</small>
        @endif
    </div>
</div>
</div>
@section('footer_js')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
    ClassicEditor
        .create( document.querySelector( '#editor-1' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
@endsection