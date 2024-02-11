@extends('layouts.admin')
@section('title', 'Nurseify - Keywords')
@section('content')
    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
        <h3>{{$ps['pagename']}}</h3>

        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="{{route('keywords.create')}}">Add Keyword</a> </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <table class="basic-table">

                <tr>
                    <th>Filter</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>date Time</th>
                    <th>Amount</th>
                    <th>Count</th>
                    <th>Action</th>
                </tr>

                @foreach($keys as $key)
                <tr>
                    <td data-label="Filter">{{$key->filter}}</td>
                    <td data-label="Title"><a href="{{route($ps['actionHed'].'.edit', $key)}}"> {{$key->title}} </a></td>
                    <td data-label="Description">{{$key->description}}</td>
                    <td data-label="date Time">{{$key->dateTime}}</td>
                    <td data-label="Amount">{{$key->amount}}</td>
                    <td data-label="Count">{{$key->count}}</td>
                    <td data-label="Action">
                        <a href="{{route($ps['actionHed'].'.edit',$key)}}" class="button small secondary">Edit</a>
                        <a href="#" class="button small warning">Del</a>
                    </td>
                </tr>
                @endforeach

            </table>
        </div>
    </div>

@endsection