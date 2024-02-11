@extends('layouts.admin')
@section('title', 'Nurseify - Add Credential')
@section('content')
<div class="dashboard-headline">
    <h3>Add Credentials</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li><a href="/admin/nurses/{{$nurse->id}}/edit">Edit Nurse</a></li>
            <li>Add Credentials</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['NurseController@update', $nurse->id], 'method' => 'POST', 'files' => 'true']) !!}
        @include('admin.nurses.partials.detail-credential')        
    {{Form::hidden('_method','PUT')}}
    {{ Form::hidden('url', URL::previous()) }}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Add Credential', ['name' => 'add_credentials', 'type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5','style' => 'float:left'])}}
        <a href="/admin/nurses/{{$nurse->id}}/edit" class="button gray ripple-effect big margin-top-30">Cancel</a>
    </div>
    {!! Form::close() !!}
</div>
@endsection