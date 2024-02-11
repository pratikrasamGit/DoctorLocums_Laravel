@extends('layouts.admin')
@section('title', 'Nurseify - Edit Credential')
@section('content')
<div class="dashboard-headline">
    <h3>Edit Credentials</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li><a href="/admin/nurses/{{$nurse->id}}/edit">Edit Nurse</a></li>
            <li>Edit Credentials</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['NurseController@editCredentialPost',$nurse->id,$certification->id], 'method' => 'POST', 'files' => 'true']) !!}
    @include('admin.nurses.partials.detail-credential')
    @if( isset($nurse->certifications) && count($nurse->certifications) > 0 )
    <div class="col-xl-12">
    <div class="submit-field">
    <!-- Attachments -->
    <div class="attachments-container margin-top-20 margin-bottom-0">
        @foreach($nurse->certifications as $certification)
        @if(Illuminate\Support\Facades\Storage::exists('assets/nurses/certifications/'.$nurse->id.'/'.$certification->certificate_image))
        <div class="attachment-box ripple-effect">
            <a href="{{ route('cred.download',[$nurse->id,$certification->id]) }}" target="_blank"><span>{{$certification->certificate_image}}</span></a>
            <a href="{{ route('cred.remove',[$nurse->id,$certification->id]) }}" class="remove-attachment" data-tippy-placement="top" title="Remove"></a>
        </div>
        @endif
        @endforeach
    </div>
    </div>
    </div>
    @endif
    {{ Form::hidden('url', URL::previous()) }}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Update Credential', ['name' => 'updates_credentials', 'type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5','style' => 'float:left'])}}
        <a href="/admin/nurses/{{$nurse->id}}/edit" class="button gray ripple-effect big margin-top-30">Cancel</a>
    </div>
    {!! Form::close() !!}
</div>
@endsection