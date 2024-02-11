@extends('layouts.admin')
@section('title', 'Nurseify - Create Department')
@section('content')
<div class="dashboard-headline">
    <h3>Add Department</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Add Department</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => 'DepartmentController@store', 'method' => 'POST']) !!}
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-line-awesome-building"></i> About Department</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
               @include('facilities.departments.detail-department')
            </div>
        </div>              
    </div>
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Create Department', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
    {!! Form::close() !!}
</div>
@endsection