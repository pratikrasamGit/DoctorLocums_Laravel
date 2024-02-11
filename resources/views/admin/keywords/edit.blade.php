@extends('layouts.admin')
@section('title', 'Nurseify - Edit Keyword')
@section('content')

    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
        <h3>{{$ps['pagename']}}</h3>

        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="/">Admin Dashboard</a></li>
                <li>{{$ps['pagename']}}</li>
            </ul>
        </nav>
    </div>

    <!-- Row --> 
    <div class="row">
        <form action="{{route($ps['actionHed'].'.update', $key)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('admin.keywords.details')
            <!-- Button -->
            <div class="col-xl-12">
                <button type="submit" class="button ripple-effect big margin-top-30">Save Changes</button>
                <!-- <a href="#" class="button ">Save Changes</a> -->
            </div>
        </form>
    </div>
    <!-- Row / End -->

@endsection