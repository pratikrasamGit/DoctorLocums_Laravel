@extends('layouts.app-sidebar')
@section('title', 'Nurseify - Edit Job')
@section('content')
<div class="dashboard-headline">
    <h3>Edit Job</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Edit Job</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['JobController@update', $job->id], 'method' => 'POST', 'files' => true]) !!}
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-feather-folder-plus"></i> About Job</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
                @include('jobs.partials.detail-job')
            </div>
        </div>     
    </div>
    {{Form::hidden('_method','PUT')}}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Update Job', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
    {!! Form::close() !!}
</div>
@endsection