@extends('layouts.admin')
@section('title', 'Nurseify - Edit Department')
@section('content')
<div class="dashboard-headline">
    <h3>Edit Department</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Edit Department</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['DepartmentController@update', $department->id], 'method' => 'POST']) !!}
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
        <div class="dashboard-box margin-top-30">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-line-awesome-users"></i> Department Users</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
            <div class="container search-panel">
            <div class="row">
                <div class="col-md-3">
                    <a href="/admin/departmentusers/{{$department->id}}/create" class="button ripple-effect">Add Department User<i class="icon-material-outline-add"></i></a>
                </div>                
            </div>
            </div> 
            @include('facilities.departments.list-users')
            </div>
        </div>     
    </div>
    {{Form::hidden('_method','PUT')}}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Update Department', ['type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
    {!! Form::close() !!}
</div>
@endsection