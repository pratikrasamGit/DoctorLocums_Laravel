@extends('layouts.admin')
@section('title', 'Nurseify - Departments')
@section('content')
<div class="dashboard-headline">
    <h3>Departments</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Departments</li>
        </ul>
    </nav>
</div>
<div class="container search-panel">
    <div class="row">
        <div class="col-md-3">
            <a href="{{route('departments.create')}}" class="button ripple-effect">Add Department <i class="icon-material-outline-add"></i></a>
        </div>
        <!-- <div class="col-md-3">
            <select class="selectpicker" multiple>
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="selectpicker" multiple>
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="submit" class="button dark ripple-effect" name="">
        </div> -->
    </div>
</div> 
<div class="container margin-bottom-30">
    <div class="row">
    @if(count($departments) > 0)
        <table class="table-one">
            <tr>
                <th width="20%">Facility</th>
                <th width="20%">Department Name</th>
                <th width="20%">Specialties</th>
                <th width="15%">Phone</th>
                <th width="15%">No. of Users</th>
                <th width="10%">Action</th>
            </tr>
            @foreach($departments as $department)
            <tr>
                <td>{{$department->facility->name}}</td>
                <td>{{$department->department_name}}</td>
                <td>{{\App\Providers\AppServiceProvider::keywordTitle($department->department_specialties)}}</td>
                <td>{{$department->department_phone}}</td>
                <td>{{$department->users->where('active',true)->count()}}</td>
                <td>
                    <a href="{{ route('departments.edit',[$department->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
                    @role('Administrator')
                    <form onsubmit="return confirm('Do you really want to delete?');" action="{{ route('departments.destroy',[$department->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="icon delete-icon" data-tippy-placement="top" title="Delete"><i class="icon-material-outline-delete"></i></button>
                    </form>
                    @endrole
                </td>
            </tr>
            @endforeach           
        </table>
        {{ $departments->appends(request()->except('page'))->links() }}
        @else
        <p>No department found.</p>
        @endif
    </div>
</div>
@endsection