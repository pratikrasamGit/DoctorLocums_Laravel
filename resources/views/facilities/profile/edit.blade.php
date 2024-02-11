@extends('layouts.app')
@section('title', 'Nurseify - Facility Profile Setup')
@section('content')
<div class="dashboard-headline">
    <h3>Profile Setup</h3>
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Profile Setup</li>
        </ul>
    </nav>
</div>
<div class="row">
{!! Form::open(['action' => ['ProfileController@facilityDetailPost', $facility->id], 'method' => 'POST', 'files' => 'true']) !!}
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-line-awesome-building"></i> About {{$facility->name}}</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
              @include('facilities.partials.detail-personal')
            </div>
        </div>
        <div class="dashboard-box margin-top-30">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-feather-share-2"></i> {{$facility->name}} Social</h3>
            </div>
            <div class="content with-padding padding-bottom-10">
            @include('facilities.partials.detail-social')
            </div>
        </div>
        
    </div>
    <div class="col-xl-12">
        {{Form::button('Save Changes', ['name' => 'update_profile','type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
{!! Form::close() !!}
</div>
@endsection