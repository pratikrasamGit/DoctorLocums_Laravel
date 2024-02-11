@extends('layouts.admin')
@section('title', 'Nurseify - Edit Facility')
@section('content')
<div class="dashboard-headline">
    <h3>Edit Facility</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Edit Facility</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['FacilitiesController@update', $facility->id], 'method' => 'POST', 'files' => true]) !!}
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
                <h3><i class="icon-material-outline-account-circle"></i> Facility Users</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
            <div class="container search-panel">
            <div class="row">
                <div class="col-md-3">
                    <a href="/admin/facilityusers/{{$facility->id}}/create" class="button ripple-effect">Add Facility User<i class="icon-material-outline-add"></i></a>
                </div>                
            </div>
            </div>
              @include('facilities.users.list-users')
            </div>
        </div>
        @if(isset($facility->departments) && count($facility->departments) > 0)
        <div class="dashboard-box margin-top-30">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-line-awesome-building-o"></i> Facility Departments</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
                @include('facilities.departments.list-departments')
            </div>
        </div>
        @endif
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
    {{Form::hidden('_method','PUT')}}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Update Facility', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5','style' => 'float:left'])}}
        <a href="/admin/facilities/" class="button gray ripple-effect big margin-top-30">Cancel</a>
    </div>
    {!! Form::close() !!}
</div>
@endsection