@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Role Interest',
'activetab' => 'roleInterest'
]
)
@section('inner-content')
{!! Form::open(['action' => ['ProfileController@roleInterestPost',$nurse->id], 'method' => 'POST', 'files' => 'true']) !!}
<div class="row">
<!-- Dashboard Box -->
<div class="col-xl-12">
    <div class="dashboard-box margin-top-0">

        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-material-outline-assessment"></i> Role Interest</h3>
        </div>
        <div class="content with-padding">
            <div class="row">
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Are you interested in serving as a preceptor for a graduate nurse?</h5>
                        <div class="radio">
                            {{ Form::radio('serving_preceptor', 1, 
                            $nurse->serving_preceptor && $nurse->serving_preceptor == true ? true : false, 
                            ['id'=>'serving_preceptor_1']) }}
                            <label for="serving_preceptor_1"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('serving_preceptor', 0, 
                            $nurse->serving_preceptor && $nurse->serving_preceptor == false ? true : false, 
                            ['id'=>'serving_preceptor_0']) }}
                            <label for="serving_preceptor_0"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Are you interested in serving as an interim nurse leader?</h5>
                        <div class="radio">
                            {{ Form::radio('serving_interim_nurse_leader', 1, 
                            $nurse->serving_interim_nurse_leader && $nurse->serving_interim_nurse_leader == true ? true : false, 
                            ['id'=>'serving_interim_nurse_leader']) }}
                            <label for="serving_interim_nurse_leader"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('serving_interim_nurse_leader', 0, 
                            $nurse->serving_interim_nurse_leader && $nurse->serving_interim_nurse_leader == false ? true : false, 
                            ['id'=>'serving_interim_nurse_leader_2']) }}
                            <label for="serving_interim_nurse_leader_2"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 hide leadersip-role">
                    <div class="submit-field">
                        <h5>Please select the leadership role you have experience in and are willing to work as an interim leader?</h5>
                        {{Form::select('leadership_roles', $leadershipRoles,  $nurse->leadership_roles, 
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('leadership_roles'), 
                        ['class' => 'selectform','placeholder' => 'Select Leadership Role']))}}
                        @if ($errors->has('leadership_roles'))
                        <small class="invalid-feedback">{{ $errors->first('leadership_roles') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Clinical educator</h5>
                        <div class="radio">
                            {{ Form::radio('clinical_educator', 1, 
                            $nurse->clinical_educator && $nurse->clinical_educator == true ? true : false, 
                            ['id'=>'clinical_educator_1']) }}
                            <label for="clinical_educator_1"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('clinical_educator', 0, 
                            $nurse->clinical_educator && $nurse->clinical_educator == false ? true : false, 
                            ['id'=>'clinical_educator_0']) }}
                            <label for="clinical_educator_0"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Daisy Award winner?</h5>
                        <div class="radio">
                            {{ Form::radio('is_daisy_award_winner', 1, 
                            $nurse->is_daisy_award_winner && $nurse->is_daisy_award_winner == true ? true : false, 
                            ['id'=>'is_daisy_award_winner_1']) }}
                            <label for="is_daisy_award_winner_1"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('is_daisy_award_winner', 0, 
                            $nurse->is_daisy_award_winner && $nurse->is_daisy_award_winner == false ? true : false, 
                            ['id'=>'is_daisy_award_winner_0']) }}
                            <label for="is_daisy_award_winner_0"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Employee of the mth, qtr, yr?</h5>
                        <div class="radio">
                            {{ Form::radio('employee_of_the_mth_qtr_yr', 1, 
                            $nurse->employee_of_the_mth_qtr_yr && $nurse->employee_of_the_mth_qtr_yr == true ? true : false, 
                            ['id'=>'employee_of_the_mth_qtr_yr_1']) }}
                            <label for="employee_of_the_mth_qtr_yr_1"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('employee_of_the_mth_qtr_yr', 0, 
                            $nurse->employee_of_the_mth_qtr_yr && $nurse->employee_of_the_mth_qtr_yr == false ? true : false, 
                            ['id'=>'employee_of_the_mth_qtr_yr_0']) }}
                            <label for="employee_of_the_mth_qtr_yr_0"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Other nursing awards?</h5>
                        <div class="radio">
                            {{ Form::radio('other_nursing_awards', 1, 
                            $nurse->other_nursing_awards && $nurse->other_nursing_awards == true ? true : false, 
                            ['id'=>'other_nursing_awards_1']) }}
                            <label for="other_nursing_awards_1"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('other_nursing_awards', 0, 
                            $nurse->other_nursing_awards && $nurse->other_nursing_awards == false ? true : false, 
                            ['id'=>'other_nursing_awards_0']) }}
                            <label for="other_nursing_awards_0"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Professional practice council?</h5>
                        <div class="radio">
                            {{ Form::radio('is_professional_practice_council', 1, 
                            $nurse->is_professional_practice_council && $nurse->is_professional_practice_council == true ? true : false, 
                            ['id'=>'is_professional_practice_council_1']) }}
                            <label for="is_professional_practice_council_1"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('is_professional_practice_council', 0, 
                            $nurse->is_professional_practice_council && $nurse->is_professional_practice_council == false ? true : false, 
                            ['id'=>'is_professional_practice_council_0']) }}
                            <label for="is_professional_practice_council_0"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Research / publications?</h5>
                        <div class="radio">
                            {{ Form::radio('is_research_publications', 1, 
                            $nurse->is_research_publications && $nurse->is_research_publications == true ? true : false, 
                            ['id'=>'is_research_publications_1']) }}
                            <label for="is_research_publications_1"><span class="radio-label"></span> Yes</label>
                        </div>
                        <div class="radio">
                            {{ Form::radio('is_research_publications', 0, 
                            $nurse->is_research_publications && $nurse->is_research_publications == false ? true : false, 
                            ['id'=>'is_research_publications_0']) }}
                            <label for="is_research_publications_0"><span class="radio-label"></span> No</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="submit-field">
                        <h5>Select Languages</h5>
                        {{Form::select('languages[]', $languages,  $nurse->languages, 
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('languages'), 
                        ['class' => 'selectform','multiple' => 'multiple']))}}
                        @if ($errors->has('languages'))
                        <small class="invalid-feedback">{{ $errors->first('languages') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="submit-field">
                        <h5>Introduce Yourself (Feel free to upload additional photos)</h5>
                        {{Form::textarea('summary', $nurse->summary, 
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('summary'), 
                        ['id' => 'editor', 'class' => 'with-border', 'cols' => '30', 'rows' => '5']))}}
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="submit-field">
                        <h5>YouTube / Vimeo Link</h5>
                        {{Form::text('nu_video', $nurse->nu_video,
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('nu_video'), 
                        ['class' => 'with-border', 'maxlength' => 255]))}}
                        @if ($errors->has('nu_video'))
                            <small class="invalid-feedback">{{ $errors->first('nu_video') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="submit-field">
                    <h5>Additional Photos</h5>            
                    <div class="uploadButton">
                        {{Form::file('additional_pictures[]',['id' => 'additional_pictures','class' => 'with-border','multiple'=> 'multiple'])}}
                    </div>                    
                    </div>
                    @if(isset($nurse->nurseAssets) && count($nurse->nurseAssets->where('filter','additional_photos'))>0)
                    <div class="single-page-section">
                    <div id="additional_photos">
                    @foreach($nurse->nurseAssets->where('filter','additional_photos') as $additional_picture)
                    @if(Illuminate\Support\Facades\Storage::exists('assets/nurses/additional_photos/'.$nurse->id.'/'.$additional_picture->name))
                    <img class="col-3" src="data:image/jpeg;base64,
                        {{ base64_encode(\Illuminate\Support\Facades\Storage::get('assets/nurses/additional_photos/'.$nurse->id.'/'.$additional_picture->name)) }}" alt="">
                    <a href="{{ route('nurse-file.remove',[$nurse->id,$additional_picture->id]) }}">remove</a>
                    @endif
                    @endforeach
                    </div>
                    </div>
                    @endif
                </div>                
                <div class="col-xl-12">
                    <div class="submit-field">
                    <h5>Additional Files (e.g., Letters of Recommendation)</h5>            
                    <div class="uploadButton">
                        {{Form::file('additional_files[]',['id' => 'additional_files','class' => 'with-border','multiple'=> 'multiple'])}}
                    </div>                    
                    </div>
                </div>
                @if(isset($nurse->nurseAssets) && count($nurse->nurseAssets->where('filter','additional_files'))>0)
                <div class="row">
                <div class="col-xl-12">
                    <div class="submit-field">
                        <!-- Attachments -->
                        <div class="attachments-container margin-top-0 margin-bottom-0">
                        @foreach($nurse->nurseAssets->where('filter','additional_files') as $additional_file)
                            @if(Illuminate\Support\Facades\Storage::exists('assets/nurses/additional_files/'.$nurse->id.'/'.$additional_file->name))
                            <div class="attachment-box ripple-effect">
                                <a href="{{ route('nurse-file.download',[$nurse->id,$additional_file->id]) }}" target="_blank"><span>{{$additional_file->name}}</span></a>
                                <a href="{{ route('nurse-file.remove',[$nurse->id,$additional_file->id]) }}" class="remove-attachment" title="Remove"></a>
                            </div>
                            @endif
                        @endforeach
                        </div>
                    </div>
                </div>
                </div>
                @endif                
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
{{Form::button('Save Changes', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
</div>
</div>
{!! Form::close() !!}
@section('footer_js')
<script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
@endsection
@endsection