@extends('layouts.admin')
@section('title', 'Nurseify - Create Keyword')
@section('content')

    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
        <h3>{{$ps['pagename']}}</h3>
    </div>

    <!-- Row --> 
    <div class="row">
        <form action="{{route($ps['actionHed'].'.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
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