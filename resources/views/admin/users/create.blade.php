@extends('layouts.admin')
@section('title', 'Nurseify - Create Admin User')
@section('content')
<div class="dashboard-headline">
    <h3>Add Admin User</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Add Admin User</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => 'AdminUsersController@store', 'method' => 'POST']) !!}
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
        {{Form::button('<i class="icon-feather-plus"></i> Create User', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
    {!! Form::close() !!}
</div>
@endsection