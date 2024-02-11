@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Edit Credential & Certification',
'activetab' => 'certifications'
]
)
@section('inner-content')
{!! Form::open(['action' => ['ProfileController@editCredentialPost',$nurse->id,$certification->id], 'method' => 'POST', 'files' => 'true']) !!}
<div class="row">
<!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <div class="content with-padding">
                @include('nurses.partials.form-credential')
                @if( isset($nurse->certifications) && count($nurse->certifications) > 0 )
                <div class="col-xl-12">
                <div class="submit-field">
                <!-- Attachments -->
                <div class="attachments-container margin-top-20 margin-bottom-0">
                    @foreach($nurse->certifications as $certification)
                    @if(Illuminate\Support\Facades\Storage::exists('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image))
                    <div class="attachment-box ripple-effect">
                        <a href="{{ route('nurse-cred.download',[$nurse->id,$certification->id]) }}" target="_blank"><span>{{$certification->certificate_image}}</span></a>
                        <a href="{{ route('nurse-cred.remove',[$nurse->id,$certification->id]) }}" class="remove-attachment" title="Remove"></a>
                    </div>
                    @endif
                    @endforeach
                </div>
                </div>
                </div>
                @endif
            </div>
        </div>      
    </div>
<div class="col-xl-12">
{{Form::button('Update Credential', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5', 'style' => 'float:left'])}}
<a href="{{route('work-history', [$nurse->id])}}" class="button gray ripple-effect big margin-top-30">Cancel</a>
</div>
</div>
{!! Form::close() !!}
@endsection