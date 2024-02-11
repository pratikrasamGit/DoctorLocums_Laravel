@extends('layouts.admin')
@section('title', 'Nurseify - Create Facility User')
@section('content')
<div class="dashboard-headline">
    <h3>Add Facility User</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Add Facility User</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['FacilityUsersController@store', $facility->id], 'method' => 'POST']) !!}
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-account-circle"></i> About User</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
              @include('users.form')                           
            </div>            
        </div>           
    </div>
    @include('users.password')
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Create User', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30 margin-right-5','style' => 'float:left'])}}
        <a href="/admin/facilities/{{$facility->id}}/edit" class="button gray ripple-effect big margin-top-30">Cancel</a>
    </div>
    {{ Form::hidden('url', URL::previous()) }}
    {!! Form::close() !!}
</div>
@endsection