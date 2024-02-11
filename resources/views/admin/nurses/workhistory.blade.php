@extends('layouts.admin')
@section('title', 'Nurseify - Add Work History')
@section('content')
<div class="dashboard-headline">
    <h3>Add Work History</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li><a href="/admin/nurses/{{$nurse->id}}/edit">Edit Nurse</a></li>
            <li>Add Work History</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['NurseController@update', $nurse->id], 'method' => 'POST', 'files' => 'true']) !!}
        @include('admin.nurses.partials.detail-workhistory')        
        {{Form::hidden('_method','PUT')}}
    {{ Form::hidden('url', URL::previous()) }}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Add Work History', ['name' => 'add_exp', 'type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5','style' => 'float:left'])}}
        <a href="/admin/nurses/{{$nurse->id}}/edit" class="button gray ripple-effect big margin-top-30">Cancel</a>
    </div>
    {!! Form::close() !!}
</div>
@endsection