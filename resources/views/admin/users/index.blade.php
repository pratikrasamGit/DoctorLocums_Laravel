@extends('layouts.admin')
@section('title', 'Nurseify - Admin Users')
@section('content')
<div class="dashboard-headline">
    <h3>Admin Users</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Admin Users</li>
        </ul>
    </nav>
</div>
<div class="container margin-bottom-30">
<div class="row">
@if(count($users) > 0)
    <table class="table-one">
        <tr>
            <th width="20%">Name</th>
            <th width="20%">Email</th>
            <th width="20%">Phone</th>
            <th width="20%">Last Login</th>
            <th width="20%">Action</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{$user->getFullNameAttribute()}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->mobile}}</td>
            <td>{{$user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A'}}</td>
            <td>
                <a href="{{ route('adminusers.edit',[$user->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
                <form onsubmit="return confirm('Do you really want to delete?');" action="{{ route('adminusers.destroy',[$user->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" class="icon delete-icon" data-tippy-placement="top" title="Delete"><i class="icon-material-outline-delete"></i></button>
                </form>
            </td>
        </tr>
        @endforeach           
    </table>
    {{ $users->appends(request()->except('page'))->links() }}
    @else
    <p>No admin user found.</p>
    @endif
</div>
</div>
@endsection