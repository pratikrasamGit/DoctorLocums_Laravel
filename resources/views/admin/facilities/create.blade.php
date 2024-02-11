@extends('layouts.admin')
@section('title', 'Nurseify - Create Facility')
@section('content')
<div class="dashboard-headline">
    <h3>Add Facility</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Add Facility</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => 'FacilitiesController@store', 'method' => 'POST', 'files' => true]) !!}
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-line-awesome-building"></i> About Facility</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
               @include('facilities.partials.detail-personal')
            </div>
        </div>
        <div class="dashboard-box margin-top-30">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-feather-share-2"></i> Facility Social</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
            @include('facilities.partials.detail-social')
            </div>
        </div>        
    </div>
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Create Facility', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
    {!! Form::close() !!}
</div>
@endsection