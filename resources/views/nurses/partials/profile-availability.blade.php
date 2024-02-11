@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Hourly Rate & Availability',
'activetab' => 'availability'
]
)
@section('inner-content')
{!! Form::open(['action' => ['ProfileController@availabilityPost',$nurse->id], 'method' => 'POST']) !!}
<div class="row">
<!-- Dashboard Box -->
<div class="col-xl-12">
    <div class="dashboard-box margin-top-0">
        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-feather-dollar-sign"></i> Hourly Rate</h3>
        </div>
        <div class="content with-padding">
            <ul class="fields-ul">
                <li>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="submit-field">
                                <div class="bidding-widget">
                                    <span class="bidding-detail">Set your <strong>hourly rate</strong></span>
                                    <div class="bidding-value margin-bottom-10">$<span id="biddingVal"></span></div>
                                    <input class="bidding-slider" type="text" name="hourly_pay_rate" value="{{$nurse->hourly_pay_rate}}" data-slider-handle="custom" data-slider-currency="$" data-slider-min="5" data-slider-max="150" data-slider-value="{{$nurse->hourly_pay_rate}}" data-slider-step="1" data-slider-tooltip="hide" />
                                </div>
                            </div>
                        </div>                                   
                    </div>                                                            
                </li>
            </ul>
        </div>
    </div>
<div class="dashboard-box margin-top-30">
<!-- Headline -->
<div class="headline">
    <h3><i class="icon-material-outline-access-alarm"></i> My Availability</h3>
</div>
<div class="content with-padding padding-bottom-0">
    <ul class="fields-ul">
        <li>
        <div class="row">
                <div class="col-xl-6">
                    <div class="submit-field">
                        <h5>Shift Duration</h5>
                        {{Form::select('shift_duration', $shifts,  $availability->shift_duration, 
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('shift_duration'), 
                        ['class' => 'selectform','placeholder' => 'Select Shifts']))}}
                        @if ($errors->has('shift_duration'))
                        <small class="invalid-feedback">{{ $errors->first('shift_duration') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="submit-field">
                        <h5>Assignment Duration</h5>
                        {{Form::select('assignment_duration', $assignmentDurations,  $availability->assignment_duration, 
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('assignment_duration'), 
                        ['class' => 'selectform','placeholder' => 'Select Assignment Duration']))}}
                        @if ($errors->has('assignment_duration'))
                        <small class="invalid-feedback">{{ $errors->first('assignment_duration') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="section-headline margin-bottom-12">
                        <h5>Preferred Shift</h5>
                    </div>
                    {{Form::select('preferred_shift', $preferredShifts,  $availability->preferred_shift, 
                    \App\Providers\AppServiceProvider::fieldAttr($errors->has('preferred_shift'), 
                    ['class' => 'selectform','placeholder' => 'Select Preferred Shift']))}}
                    @if ($errors->has('preferred_shift'))
                    <small class="invalid-feedback">{{ $errors->first('preferred_shift') }}</small>
                    @endif
                </div>
                <div class="col-xl-6">
                    <div class="submit-field">
                        <h5>Select Preferred Days of the Week (select all that apply)</h5>
                        {{Form::select('days_of_the_week[]', $weekDays,  $availability->days_of_the_week, 
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('days_of_the_week'), 
                        ['class' => 'selectform','multiple' => 'multiple']))}}
                        @if ($errors->has('days_of_the_week'))
                        <small class="invalid-feedback">{{ $errors->first('days_of_the_week') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">                                   
                <div class="col-xl-6">
                    <div class="submit-field">
                        <h5>Earliest Start Date</h5>
                        {{Form::date('earliest_start_date', $availability->earliest_start_date, 
                            \App\Providers\AppServiceProvider::fieldAttr($errors->has('earliest_start_date'),
                            ['id' => 'earliest_start_date','class' => 'with-border']
                            ))}}
                        @if ($errors->has('earliest_start_date'))
                        <small class="invalid-feedback">{{ $errors->first('earliest_start_date') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="section-headline margin-bottom-12">
                        <h5>Preferred Geography</h5>
                    </div>
                    {{Form::select('work_location', $geographicPreferences,  $availability->work_location, 
                    \App\Providers\AppServiceProvider::fieldAttr($errors->has('work_location'), 
                    ['class' => 'selectform','placeholder' => 'Select Work Location']))}}
                    @if ($errors->has('work_location'))
                    <small class="invalid-feedback">{{ $errors->first('work_location') }}</small>
                    @endif
                </div>
            </div>
        </li>
    </ul>
</div>
</div>
</div>
<div class="col-xl-12">
{{Form::button('Save Changes', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
</div>
</div>
{!! Form::close() !!}
@endsection