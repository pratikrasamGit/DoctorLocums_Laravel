@extends('layouts.admin')
@section('title', 'Nurseify - Facilities')
@section('content')
<div class="dashboard-headline">
    <h3>Facilities</h3>
    <!-- Breadcrumbs -->
    <nav id="breadcrumbs" class="dark">
        <ul>
            <li><a href="/">Dashboard</a></li>
            <li>Facilities</li>
        </ul>
    </nav>
</div>
<div class="container search-panel">
{!! Form::open(['action' => 'FacilitiesController@search', 'method' => 'POST']) !!}
    <div class="row">    
        <div class="col-md-6">
            @include('searchform.form')
        </div>
        <div class="col-md-3">
            {{Form::button('<i class="icon-material-outline-search" aria-hidden="true"></i> Search Facilities', ['name' => 'search', 'type' => 'submit','class'=>'button dark ripple-effect'])}}
        </div>
        <div class="col-md-3">
           @if( \Request::get('search_text') )
			<a class="button dark ripple-effect" href="{{route('facilities.index')}}"><i class="icon-line-awesome-times-circle-o" aria-hidden="true"></i>Reset</a>
			@endif
        </div>    
    </div>
{!! Form::close() !!}    
</div>
<div class="container margin-bottom-30">
    <div class="row">
    @if(count($facilities) > 0)
        <table class="table-one">
            <tr>
                <th width="20%">Name</th>
                <th width="20%">Type</th>
                <th width="20%">Email</th>
                <th width="20%">Phone</th>
                <th width="10%">Action</th>
            </tr>
            @foreach($facilities as $facility)
            <tr>
                <td>{{$facility->name}}</td>
                <td>{{\App\Providers\AppServiceProvider::keywordTitle($facility->type)}}</td>
                <td>{{$facility->facility_email}}</td>
                <td>{{$facility->facility_phone}}</td>
                <td>
                    <a href="{{ route('facilities.edit',[$facility->id]) }}" class="icon edit-icon" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit"></i></a>
                    @role('Administrator|Admin')
                    <form onsubmit="return confirm('Do you really want to delete?');" action="{{ route('facilities.destroy',[$facility->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="icon delete-icon" data-tippy-placement="top" title="Delete"><i class="icon-material-outline-delete"></i></button>
                    </form>
                    @endrole
                </td>
            </tr>
            @endforeach           
        </table>
        {{ $facilities->appends(request()->except('page'))->links() }}
        @else
        <p>No facility found.</p>
        @endif
    </div>
</div>
@endsection