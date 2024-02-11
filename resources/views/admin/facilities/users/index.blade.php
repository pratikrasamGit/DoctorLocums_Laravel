@extends('layouts.admin')
@section('title', 'Nurseify - Facility Users')
@section('content')
<div class="dashboard-headline">
    <h3>Facility Users</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Facility Users</li>
        </ul>
    </nav>
</div>
<div class="container margin-bottom-30">
<div class="row">
@if(count($users) > 0)
    <table class="table-one">
        <tr>
            <th width="25%">Name</th>
            <th width="25%">Email</th>
            <th width="25%">Phone</th>
            <th width="25%">Action</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{$user->getFullNameAttribute()}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->mobile}}</td>
            <td>
                <a href="{{ route('facilityusers.edit',[$user->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>                
            </td>
        </tr>
        @endforeach           
    </table>
    {{ $users->appends(request()->except('page'))->links() }}
    @else
    <p>No facility user found.</p>
    @endif
</div>
</div>
@endsection