@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Work History & Certifications',
'activetab' => 'certifications'
]
)
@section('inner-content')
{!! Form::open(['action' => ['ProfileController@certificationsPost',$nurse->id], 'method' => 'POST', 'files' => 'true']) !!}
<div class="row">
<!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline resume-upload">
                <h3><i class="icon-feather-briefcase"></i> Choose File
                    <div class="uploadButton margin-top-0">
                        <input class="uploadButton-input" type="file" name="resume" accept="application/pdf,
                        application/msword,
                        application/vnd.openxmlformats-officedocument.wordprocessingml.document" id="uploadResume">
                        <label onclick="uploadResume.click()" class="uploadButton-button ripple-effect" for="upload">Upload Files</label>
                    </div>
            @if( isset($nurse->resume) && $nurse->resume )
            @if(Illuminate\Support\Facades\Storage::exists('assets/nurses/resumes/'.$nurse->id.'/'.$nurse->resume))
            <a href="{{route('nurse-cv.download', [$nurse->id])}}" target="_blank"><i class="icon-line-awesome-file-pdf-o"></i>View Resume </a>
            @endif
            @else
            <a href="{{route('resume.view.media.nurse',[$nurse->id])}}" target="_blank"><i class="icon-line-awesome-file-pdf-o"></i>View Resume </a>
            @endif  
            OR <a href="{{route('add-work-history', [$nurse->id])}}">Add Work History <i class="icon-material-outline-add"></i></a></h3>
            </div>
            {{Form::button('Upload', ['id'=> 'upload_resume','name'=> 'upload_resume','type' => 'submit','class'=>'hide'])}}
            <div class="content with-padding">
                @if(count($nuexperience) > 0)
                <div class="row list-exp">
                    <div class="col-xl-12">
                        <table class="basic-table">
                            <tbody>
                                <tr>
                                    <th>Organization Name</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Facility Type</th>
                                    <th>Start Date</th>
                                    <th></th>
                                </tr>
                                @foreach($nuexperience as $nuexp)
                                <tr>
                                    <td data-label="Column 1">{{$nuexp->organization_name}}</td>
                                    <td data-label="Column 2">{{$nuexp->exp_city}}</td>
                                    <td data-label="Column 3">{{$nuexp->exp_state}}</td>
                                    <td data-label="Column 4">{{\App\Providers\AppServiceProvider::keywordTitle($nuexp->facility_type)}}</td>
                                    <td data-label="Column 5">{{$nuexp->start_date ? date("d-m-Y", strtotime($nuexp->start_date)) : 'N/A'}}</td>
                                    <td data-label="Column 6"><a href="{{ route('edit-work-history',[$nurse->id,$nuexp->id]) }}" class="icon edit-icon" title="Edit"><i class="icon-feather-edit"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif                
            </div>
        </div>
        <div class="dashboard-box margin-top-30">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-history"></i> Experience</h3>
            </div>
            <div class="content with-padding padding-bottom-0">
                <ul class="fields-ul">
                    <li>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Highest Nursing Degree</h5>
                                    {{Form::select('highest_nursing_degree', $nursingDegrees,  $nurse->highest_nursing_degree, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('highest_nursing_degree'), 
                                ['class' => 'selectform','placeholder' => 'Select Highest Nursing Degree']))}}
                                    @if ($errors->has('highest_nursing_degree'))
                                    <small class="invalid-feedback">{{ $errors->first('highest_nursing_degree') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="submit-field">
                                    <h5>College / University Name</h5>
                                    {{Form::text('college_uni_name', $nurse->college_uni_name, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('college_uni_name'), 
                                ['class' => 'with-border', 'maxlength' => 255]))}}
                                    @if ($errors->has('college_uni_name'))
                                    <small class="invalid-feedback">{{ $errors->first('college_uni_name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>City</h5>
                                    {{Form::text('college_uni_city', $nurse->college_uni_city, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('college_uni_city'), 
                                ['class' => 'with-border', 'maxlength' => 20]))}}
                                    @if ($errors->has('college_uni_city'))
                                    <small class="invalid-feedback">{{ $errors->first('college_uni_city') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>State</h5>
                                    {{Form::select('college_uni_state', $states,  $nurse->college_uni_state, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('college_uni_state'), 
                                ['class' => 'selectform with-border']))}}
                                    @if ($errors->has('college_uni_state'))
                                    <small class="invalid-feedback">{{ $errors->first('college_uni_state') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Country</h5>
                                    {{Form::text('college_uni_country', $nurse->college_uni_country, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('college_uni_country'), 
                                ['class' => 'with-border', 'maxlength' => 20]))}}
                                    @if ($errors->has('college_uni_country'))
                                    <small class="invalid-feedback">{{ $errors->first('college_uni_country') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Acute Care Facility Experience</h5>
                                    {{Form::text('experience_as_acute_care_facility', $nurse->experience_as_acute_care_facility, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('experience_as_acute_care_facility'), 
                                ['class' => 'with-border', 'maxlength' => 6, 'placeholder' => 'Ex. 2 Years']))}}
                                    @if ($errors->has('experience_as_acute_care_facility'))
                                    <small class="invalid-feedback">{{ $errors->first('experience_as_acute_care_facility') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Non-Acute Care Nursing Experience</h5>
                                    {{Form::text('experience_as_ambulatory_care_facility', $nurse->experience_as_ambulatory_care_facility, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('experience_as_ambulatory_care_facility'), 
                                ['class' => 'with-border', 'maxlength' => 6, 'placeholder' => 'Ex. 2 Years']))}}
                                    @if ($errors->has('experience_as_ambulatory_care_facility'))
                                    <small class="invalid-feedback">{{ $errors->first('experience_as_ambulatory_care_facility') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Cerner</h5>
                                    {{Form::select('ehr_proficiency_cerner', $ehrProficienciesExp,  $nurse->ehr_proficiency_cerner, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('ehr_proficiency_cerner'), 
                                ['class' => 'selectform','placeholder' => 'Select Years of Experience']))}}
                                    @if ($errors->has('ehr_proficiency_cerner'))
                                    <small class="invalid-feedback">{{ $errors->first('ehr_proficiency_cerner') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Meditech</h5>
                                    {{Form::select('ehr_proficiency_meditech', $ehrProficienciesExp,  $nurse->ehr_proficiency_meditech, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('ehr_proficiency_meditech'), 
                                ['class' => 'selectform','placeholder' => 'Select Years of Experience']))}}
                                    @if ($errors->has('ehr_proficiency_meditech'))
                                    <small class="invalid-feedback">{{ $errors->first('ehr_proficiency_meditech') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Epic</h5>
                                    {{Form::select('ehr_proficiency_epic', $ehrProficienciesExp,  $nurse->ehr_proficiency_epic, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('ehr_proficiency_epic'), 
                                ['class' => 'selectform','placeholder' => 'Select Years of Experience']))}}
                                    @if ($errors->has('ehr_proficiency_epic'))
                                    <small class="invalid-feedback">{{ $errors->first('ehr_proficiency_epic') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Other</h5>
                                    {{Form::text('ehr_proficiency_other', $nurse->ehr_proficiency_other, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('ehr_proficiency_other'), 
                                ['class' => 'with-border', 'maxlength' => 100]))}}
                                    @if ($errors->has('ehr_proficiency_other'))
                                    <small class="invalid-feedback">{{ $errors->first('ehr_proficiency_other') }}</small>
                                    @endif
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
            <h3><i class="icon-line-awesome-files-o"></i> National Certifications & Credentials 
            <a href="{{route('nurse-add-credentials',[$nurse->id])}}">Add Credentials <i class="icon-material-outline-add"></i></a></h3>
        </div>
        <div class="content with-padding padding-bottom-0">
           @if($nurse->certifications && count($nurse->certifications) > 0)
            <div class="row">
                <div class="col-xl-12">
                    <div class="submit-field">
                        <h5>Attached Certifications & Credentials</h5>
                        <!-- Attachments -->
                        <div class="attachments-container margin-top-0 margin-bottom-0">
                           @foreach($nurse->certifications as $certification)
                            <div class="attachment-box ripple-effect">
                            <a href="{{ route('nurse-edit-credentials',[$nurse->id,$certification->id]) }}"><span>{{\App\Providers\AppServiceProvider::keywordTitle($certification->type)}}</span></a>
                                <span>Effective Date : {{$certification->effective_date ? date("d-m-Y", strtotime($certification->effective_date)) : 'N/A'}}                                
                                      Expiration Date : {{$certification->expiration_date ? date("d-m-Y", strtotime($certification->expiration_date)) : 'N/A'}}</span>
                                <a href="{{ route('nurse-credential.remove',[$nurse->id,$certification->id]) }}" class="remove-attachment" title="Remove"></a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    </div>
<div class="col-xl-12">
{{Form::button('Save Changes', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
</div>
</div>
{!! Form::close() !!}
@endsection