@extends('layouts.admin')
@section('title', 'Nurseify - Trashed Nurses')
@section('content')
<div class="dashboard-headline">
    <h3>Trashed Nurses</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Trashed Nurses</li>
        </ul>
    </nav>
</div>
<div class="container margin-bottom-30">
    <div class="row">
    @if(count($nurses) > 0)
        <table class="table-one">
            <tr>
                <th width="30%">Name</th>
                <th width="30%">Email</th>
                <th width="15%">Phone</th>
                <th width="10%">Licence Number</th>
                <th width="15%">Action</th>
            </tr>
            @foreach($nurses as $nurse)
            <tr>
                <td>{{$nurse->user->getFullNameAttribute()}}</td>
                <td>{{$nurse->user->email}}</td>
                <td>{{$nurse->user->mobile}}</td>
                <td>{{$nurse->nursing_license_number}}</td>
                <td>
                    @role('Administrator')
                    <a onclick="return confirm('Do you really want to Restore?');" href="{{ route('nurses-restore',['id' => $nurse->id]) }}" class="icon restore-icon" data-tippy-placement="top" title="Restore"><i class="icon-material-outline-redo"></i></a>
                    @endrole
                </td>                
            </tr>
            @endforeach           
        </table>
        {{ $nurses->appends(request()->except('page'))->links() }}
        @else
        <p>No Nurse found.</p>
        @endif
    </div>
</div>
@endsection