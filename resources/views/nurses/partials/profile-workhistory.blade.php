@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Add Work History',
'activetab' => 'certifications'
]
)
@section('inner-content')
{!! Form::open(['action' => ['ProfileController@certificationsPost',$nurse->id], 'method' => 'POST']) !!}
<div class="row">
<!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <div class="content with-padding">
                @include('nurses.partials.form-workhistory')
            </div>
        </div>      
    </div>
<div class="col-xl-12">
{{Form::button('Add Work History', ['name'=> 'add_exp', 'type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5','style' => 'float:left'])}}
<a href="{{route('work-history', [$nurse->id])}}" class="button gray ripple-effect big margin-top-30">Cancel</a>
</div>
</div>
{!! Form::close() !!}
@endsection