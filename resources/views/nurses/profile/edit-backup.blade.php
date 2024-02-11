@extends('layouts.app')

@section('content')
<div class="dashboard-headline">
    <h3>Profile Setup</h3>
    <!-- <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Profile Setup</li>
        </ul>
    </nav> -->
</div>
{!! Form::open(['action' => ['ProfileController@update', $nurse->id], 'method' => 'POST', 'files' => 'true']) !!}
<div class="row">
    @include('nurses.profile.details')
    {{Form::hidden('_method','PUT')}}
    <div class="col-xl-12">
        {{Form::button('Save Changes', ['name' => 'update_profile','type' => 'submit','class'=>'button ripple-effect big margin-top-30'])}}
    </div>
</div>
{!! Form::close() !!}
@endsection



@extends('layouts.app')

@section('title', 'Nurseify - ' . $subtitle)

@section('content')
<div class="dashboard-headline">
    <h3>Profile Setup</h3>
    <!-- <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Profile Setup</li>
        </ul>
    </nav> -->
</div>
<div class="row">
    <div class="col-xl-12">
        <!-- Tabs Container -->
        <div class="tabs tabs-num">
            <div class="tabs-header">
                <ul>
                    <li class="@if($activetab == 'personalInfo') active @endif"><a href="{{route('personal-detail', [$nurse->id])}}">Personal Info <span>My Details</span></a></li>
                    <li class="@if($activetab == 'scheduleOnboarding') active @endif"><a href="{{route('schedule-onboarding', [$nurse->id])}}">Schedule Onboarding <span>Meeting</span></a></li>
                    <li class="@if($activetab == 'availability') active @endif"><a href="{{route('availability', [$nurse->id])}}">Hourly Rate <span>& Availability</span></a></li>
                    <li class="@if($activetab == 'certifications') active @endif"><a href="{{route('work-history', [$nurse->id])}}"> Work History <span>& Certifications</span></a></li>
                    <li class="@if($activetab == 'roleInterest') active @endif"><a href="{{route('role-interest', [$nurse->id])}}">Role <span>Interest</span></a></li>
                </ul>                
            </div>
            <!-- Tab Content -->
            <div class="tabs-content">
                @yield('inner-content')              
            </div>
        </div>
        <!-- Tabs Container / End -->
    </div>
</div>
@endsection