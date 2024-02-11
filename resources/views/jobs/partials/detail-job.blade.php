<div class="row">
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Preferred Assignment Duration</h5>
            {{Form::select('preferred_assignment_duration', $assignmentDurations,  $job->preferred_assignment_duration, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_assignment_duration'), 
            ['class' => 'selectform', 'placeholder' => 'Select Assignment Duration']))}}
            @if ($errors->has('preferred_assignment_duration'))
            <small class="invalid-feedback">{{ $errors->first('preferred_assignment_duration') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Seniority Level</h5>
            {{Form::select('seniority_level', $seniorityLevels,  $job->seniority_level, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('seniority_level'), 
            ['class' => 'selectform', 'placeholder' => 'Select Seniority Level']))}}
            @if ($errors->has('seniority_level'))
            <small class="invalid-feedback">{{ $errors->first('seniority_level') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Job Function</h5>
            {{Form::select('job_function', $jobFunctions,  $job->job_function, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('job_function'), 
            ['class' => 'selectform', 'placeholder' => 'Select Job Function']))}}
            @if ($errors->has('job_function'))
            <small class="invalid-feedback">{{ $errors->first('job_function') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Preferred Specialty</h5>
            {{Form::select('preferred_specialty', $specialities,  $job->preferred_specialty, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_specialty'), 
            ['class' => 'selectform', 'placeholder' => 'Select Specialty']))}}
            @if ($errors->has('preferred_specialty'))
            <small class="invalid-feedback">{{ $errors->first('preferred_specialty') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Preferred Shift Duration</h5>
            {{Form::select('preferred_shift_duration', $shifts,  $job->preferred_shift_duration, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_shift_duration'), 
            ['class' => 'selectform', 'placeholder' => 'Select Shift Duration']))}}
            @if ($errors->has('preferred_shift_duration'))
            <small class="invalid-feedback">{{ $errors->first('preferred_shift_duration') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Preferred Work Location</h5>
            {{Form::select('preferred_work_location', $geographicPreferences,  $job->preferred_work_location, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_work_location'), 
            ['class' => 'selectform', 'placeholder' => 'Select Work Location']))}}
            @if ($errors->has('preferred_work_location'))
            <small class="invalid-feedback">{{ $errors->first('preferred_work_location') }}</small>
            @endif
        </div>
    </div>

    {{-- new update jan 10 --}}
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Preferred Shift</h5>
            {{Form::select('preferred_shift', $getPreferredShift,  $job->preferred_shift, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_shift'), 
            ['class' => 'selectform', 'placeholder' => 'Select preferred shift']))}}
            @if ($errors->has('preferred_shift'))
            <small class="invalid-feedback">{{ $errors->first('preferred_shift') }}</small>
            @endif
        </div>
    </div>

    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Start Date</h5>
            <input min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime(date('Y-m-d'). ' + 90 days')) }}" name="start_date" id="start_date" type="date" class="form-control" placeholder="Date of birth" value="{{ date('Y-m-d',strtotime($job->start_date)) }}"/>
            @if ($errors->has('start_date'))
                <small class="invalid-feedback">{{ $errors->first('start_date') }}</small>
            @endif
        </div>
    </div>

    <div class="col-xl-4">
        <div class="submit-field">
            <h5>End Date</h5>
            <input min="{{ date('Y-m-d', strtotime(date('Y-m-d'). ' + 90 days')) }}" name="end_date" id="end_date" type="date" class="form-control" placeholder="Date of birth" value="{{ date('Y-m-d',strtotime($job->end_date)) }}"/>
            @if ($errors->has('end_date'))
                <small class="invalid-feedback">{{ $errors->first('end_date') }}</small>
            @endif
        </div>
    </div>
    {{-- new update jan 10 --}}


    <div class="col-xl-6">
        <div class="submit-field">
            <h5>Preferred Experience</h5>
            {{Form::text('preferred_experience', $job->preferred_experience, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_experience'), 
            ['class' => 'with-border', 'maxlength' => 6, 'placeholder' => 'Ex. 2 Years']))}}
            @if ($errors->has('preferred_experience'))
            <small class="invalid-feedback">{{ $errors->first('preferred_experience') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-6">
        <div class="submit-field">
            <h5>Select Preferred Days of the Week (select all that apply)</h5>
            {{Form::select('preferred_days_of_the_week[]', $weekDays,  $job->preferred_days_of_the_week, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_days_of_the_week'), 
            ['class' => 'selectform','multiple' => 'multiple']))}}
            @if ($errors->has('preferred_days_of_the_week'))
            <small class="invalid-feedback">{{ $errors->first('preferred_days_of_the_week') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Cerner</h5>
            {{Form::select('job_cerner_exp', $ehrProficienciesExp,  $job->job_cerner_exp, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('job_cerner_exp'), 
            ['class' => 'selectform','placeholder' => 'Select Years of Experience']))}}
            @if ($errors->has('job_cerner_exp'))
            <small class="invalid-feedback">{{ $errors->first('job_cerner_exp') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Meditech</h5>
            {{Form::select('job_meditech_exp', $ehrProficienciesExp,  $job->job_meditech_exp, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('job_meditech_exp'), 
            ['class' => 'selectform','placeholder' => 'Select Years of Experience']))}}
            @if ($errors->has('job_meditech_exp'))
            <small class="invalid-feedback">{{ $errors->first('job_meditech_exp') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Epic</h5>
            {{Form::select('job_epic_exp', $ehrProficienciesExp,  $job->job_epic_exp, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('job_epic_exp'), 
            ['class' => 'selectform','placeholder' => 'Select Years of Experience']))}}
            @if ($errors->has('job_epic_exp'))
            <small class="invalid-feedback">{{ $errors->first('job_epic_exp') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="submit-field">
            <h5>Other</h5>
            {{Form::text('job_other_exp', $job->job_other_exp, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('job_other_exp'), 
            ['class' => 'with-border', 'maxlength' => 100]))}}
            @if ($errors->has('job_other_exp'))
            <small class="invalid-feedback">{{ $errors->first('job_other_exp') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-12">
        <div class="submit-field">
            <div class="bidding-widget">
                <span class="bidding-detail">Preferred <strong>hourly rate</strong></span>
                <div class="bidding-value margin-bottom-10">$<span id="biddingVal"></span></div>
                <input class="bidding-slider" type="text" name="preferred_hourly_pay_rate" value="{{$job->preferred_hourly_pay_rate}}" data-slider-handle="custom" data-slider-currency="$" data-slider-min="5" data-slider-max="150" data-slider-value="{{$job->preferred_hourly_pay_rate}}" data-slider-step="1" data-slider-tooltip="hide" />
            </div>
            @if ($errors->has('preferred_hourly_pay_rate'))
            <small class="invalid-feedback">{{ $errors->first('preferred_hourly_pay_rate') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-12">
        <div class="submit-field">
            <h5>Description</h5>
            {{Form::textarea('description', $job->description, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('description'), 
            ['id' => 'editor', 'class' => 'with-border', 'cols' => '30', 'rows' => '5']))}}
        </div>
    </div>
    <div class="col-xl-12">
        <div class="submit-field">
            <h5>Responsibilities</h5>
            {{Form::textarea('responsibilities', $job->responsibilities, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('responsibilities'), 
            ['id' => 'editor-res', 'class' => 'with-border', 'cols' => '30', 'rows' => '5']))}}
        </div>
    </div>
    <div class="col-xl-12">
        <div class="submit-field">
            <h5>Qualifications</h5>
            {{Form::textarea('qualifications', $job->qualifications, 
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('qualifications'), 
            ['id' => 'editor-qua', 'class' => 'with-border', 'cols' => '30', 'rows' => '5']))}}
        </div>
    </div>
    <div class="col-xl-12">
        <div class="submit-field">
            <h5>YouTube / Vimeo Link</h5>
            {{Form::text('job_video', $job->job_video,
            \App\Providers\AppServiceProvider::fieldAttr($errors->has('job_video'), 
            ['class' => 'with-border', 'maxlength' => 255]))}}
            @if ($errors->has('job_video'))
            <small class="invalid-feedback">{{ $errors->first('job_video') }}</small>
            @endif
        </div>
    </div>
    <div class="col-xl-12">
        <div class="submit-field">
            <h5>Photos</h5>
            <div class="uploadButton">
                {{Form::file('job_photos[]',['id' => 'job_photos','class' => 'with-border','multiple'=> 'multiple'])}}
            </div>
        </div>
        @if(isset($job->jobAssets) && count($job->jobAssets->where('filter','job_photos'))>0)
        <div class="single-page-section">
        <div id="single-job-map-container">
        @foreach($job->jobAssets->where('filter','job_photos') as $job_photo)
        @if(Illuminate\Support\Facades\Storage::exists('assets/jobs/'.$job->id.'/'.$job_photo->name))
        <img class="col-3" src="data:image/jpeg;base64,
            {{ base64_encode(\Illuminate\Support\Facades\Storage::get('assets/jobs/'.$job->id.'/'.$job_photo->name)) }}" alt="">
        <a href="/job/{{$job->id}}/asset/{{$job_photo->id}}/remove">remove</a>
        @endif
        @endforeach
        </div>
        </div>
        @endif
    </div>
    <div class="col-xl-12">
        <div class="checkbox">
            <input type="checkbox" name="active" id="active" {{$job->active ? 'checked=checked' : '' }}>
            <label for="active"><span class="checkbox-icon"></span> {{ __('Active') }}</label>
        </div>
    </div>
</div>
@section('footer_js')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor-res'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor-qua'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection