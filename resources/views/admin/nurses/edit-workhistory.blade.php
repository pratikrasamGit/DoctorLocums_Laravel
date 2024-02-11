@extends('layouts.admin')
@section('title', 'Nurseify - Edit Work History')
@section('content')
<div class="dashboard-headline">
    <h3>Edit Work History</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li><a href="/admin/nurses/{{$nurse->id}}/edit">Edit Nurse</a></li>
            <li>Edit Work History</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['NurseController@editWorkHistoryPost', $nurse->id,$experience->id], 'method' => 'POST', 'files' => 'true']) !!}
        @include('admin.nurses.partials.detail-workhistory')        
    {{ Form::hidden('url', URL::previous()) }}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Update Work History', ['name' => 'update_exp', 'type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5','style' => 'float:left'])}}
        <a href="/admin/nurses/{{$nurse->id}}/edit" class="button gray ripple-effect big margin-top-30">Cancel</a>
    </div>
    {!! Form::close() !!}
</div>
@endsection