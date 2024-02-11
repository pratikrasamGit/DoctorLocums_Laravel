@extends('layouts.admin')
@section('title', 'Nurseify - Create Job')
@section('content')
<div class="dashboard-headline">
    <h3>Add Job</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Add Job</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => 'JobController@store', 'method' => 'POST', 'files' => true]) !!}
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-feather-folder-plus"></i> About Job</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
            <div class="row">
                <div class="col-xl-4">
                    <div class="submit-field">
                        <h5>Select Facility</h5>
                        {{Form::select('facility_id', $facilities,  $job->facility_id, 
                        \App\Providers\AppServiceProvider::fieldAttr($errors->has('facility_id'), 
                        ['class' => 'selectform', 'placeholder' => 'Select Facility']))}}
                        @if ($errors->has('facility_id'))
                        <small class="invalid-feedback">{{ $errors->first('facility_id') }}</small>
                        @endif
                    </div>
                </div>
            </div>
               @include('jobs.partials.detail-job')
            </div>
        </div>             
    </div>
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Create Job', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
    {!! Form::close() !!}
</div>
@endsection