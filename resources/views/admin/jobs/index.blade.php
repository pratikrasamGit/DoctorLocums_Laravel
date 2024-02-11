@extends('layouts.admin')
@section('title', 'Nurseify - Jobs')
@section('content')
<div class="dashboard-headline">
    <h3>Jobs</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Jobs</li>
        </ul>
    </nav>
</div>
<!-- <div class="container search-panel">
    <div class="row">
        <div class="col-md-3">
            <input class="with-border" placeholder="Placeholder">
        </div>
        <div class="col-md-3">
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
        </div>
    </div>
</div> -->
<div class="container margin-bottom-30">
    <div class="row">
    @if(count($jobs) > 0)
        <table class="table-one">
            <tr>
                <th width="20%">Facility</th>
                <th width="20%">Preferred Specialty</th>
                <th width="15%">Preferred Work Location</th>
                <th width="15%">Preferred Hourly Pay Rate</th>
                <th width="15%">Preferred Experience</th>
                <th width="10%">Status</th>
                <th width="15%">Action</th>
            </tr>
            @foreach($jobs as $job)
            <tr>
                <td>{{$job->facility ? $job->facility->name : ''}}</td>
                <td>{{\App\Providers\AppServiceProvider::keywordTitle($job->preferred_specialty)}}</td>
                <td>{{\App\Providers\AppServiceProvider::keywordTitle($job->preferred_work_location)}}</td>
                <td>${{$job->preferred_hourly_pay_rate}}</td>
                <td>{{$job->preferred_experience}}+ Years</td>
                <td>{{$job->active ? "Active" : "Inactive"}}</td>
                <td>
                    <a href="{{ route('jobs.edit',[$job->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
                    <a href="/browse-jobs/{{$job->id}}" target="_blank" class="icon view-icon" data-tippy-placement="top" title="View"><i class="icon-feather-eye"></i></a>
                    @role('Administrator|Admin')
                    <form onsubmit="return confirm('Do you really want to delete?');" action="{{ route('jobs.destroy',[$job->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="icon delete-icon" data-tippy-placement="top" title="Delete"><i class="icon-material-outline-delete"></i></button>
                    </form>
                    @endrole
                </td>
            </tr>
            @endforeach           
        </table>
        {{ $jobs->appends(request()->except('page'))->links() }}
        @else
        <p>No Job found.</p>
        @endif
    </div>
</div>
@endsection