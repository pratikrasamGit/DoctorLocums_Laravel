@extends('layouts.admin')
@section('title', 'Nurseify - Edit Nurse')
@section('content')
<div class="dashboard-headline">
    <h3>Edit Nurse</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Edit Nurse</li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Dashboard Box -->
    {!! Form::open(['action' => ['NurseController@update', $nurse->id], 'method' => 'POST', 'files' => 'true']) !!}
        @include('admin.nurses.partials.detail-personal')
        @include('admin.nurses.partials.detail-availability')
        @include('admin.nurses.partials.detail-certificates')
        @include('admin.nurses.partials.detail-assessment')
        @include('admin.nurses.partials.detail-verify')
        @include('admin.nurses.partials.detail-nurse_liability_insurance')
    {{Form::hidden('_method','PUT')}}
    <div class="col-xl-12">
        {{Form::button('<i class="icon-feather-plus"></i> Update Nurse', ['name' => 'update_profile', 'type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
    {!! Form::close() !!}
</div>
@endsection